<?php

    $sourceName = null;
    if (isset($_REQUEST['source']))
        $sourceName = $_REQUEST['source'];
    
    $verifier = null;
    if (isset($_REQUEST['verifier']))
    {
        $verifierName = $_REQUEST['verifier'];
        if (isset($_REQUEST[$verifierName]))
        {
            $verifier = $_REQUEST[$verifierName];
        }
    }
	
	// We may need to redirect back to a private Poodle instance.
	$callbackUri = null;
	if (isset($_REQUEST['callback']))
	{
		// URL specified via a parameter
		$callbackUri = $_REQUEST['callback'];
	}
	else
	{
		// URL specified via base64-encoded sub-path.
		$requestUri = $_SERVER['REDIRECT_URL'];
		$lastSlashStr = strrchr($requestUri, '/');
		if ($lastSlashStr !== false)
		{
			$lastSlashStr = substr($lastSlashStr, 1);
			$callbackUri = base64_decode(strtr($lastSlashStr, '-_,', '+/='));
		}
	}
	if ($callbackUri != null && strlen($callbackUri) > 0)
	{
		// Let's add any parameters specified by the authorizing service.
		foreach ($_REQUEST as $key => $value)
		{
			if ($key == 'callback' ||
				$key == 'source' ||
				$key == 'verifier')
				continue;
			if (strncmp($key, '__', 2) == 0)
				continue;
			
			$callbackUri .= "&" . $key . "=" . $value;
		}
		//header("Location: " + $redirectUri);
		echo '<html><body>';
		echo '<a href="'.$callbackUri.'">Click Here</a>';
		echo '<pre><code>'.print_r($_SERVER, true).'</code></pre>';
		echo '</body></html>';
		exit;
	}
?>
<html>
    <body>
        <h1>Poodle has been authorized<?php if ($sourceName != null) echo " on ".$sourceName; ?></h1>
        <?php if ($verifier != null) { ?>
        <p>Copy this code into the Poodle application:</p>
        <h2><?php echo $verifier ?></h2>
        <?php } ?>
        <p>Here's what we got, for what it's worth:</p>
        <pre>
            <code>
                <?php
print_r($_REQUEST);
                ?>
            </code>
        </pre>
    </body>
</html>