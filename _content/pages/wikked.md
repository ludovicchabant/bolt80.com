---
title: Wikked
layout: wikked
navigation:
    overview: Overview
    installation: Installation
    quickstart: Quick Start
    concepts: Concepts
    editing: Editing Pages
    syntax: Wiki Syntax
    config: Configuration
    deploy: Deployment
    limitations: Limitations
    support: Support
---

<a id="overview"></a>

**Wikked** is a [wiki][] engine that stores its data in plain text files using
a common revision control system like [Mercurial][] or [Git][]. It's mostly
suitable for an individual, family, or small team.

The source code is available on [Github][] and [BitBucket][].

[wiki]: https://en.wikipedia.org/wiki/Wiki
[git]: http://git-scm.com/
[mercurial]: http://mercurial.selenic.com/
[github]: https://github.com/ludovicchabant/Wikked
[bitbucket]: https://bitbucket.org/ludovicchabant/wikked


<a id="installation"></a>

## Installation

### From the package index

You need Python 2.7 to use Wikked. Then, the easiest way to install it is to use
`pip`:

    pip install wikked

If you're using `easy_install` instead:

    easy_install wikked

And if you want to use the very latest (and potentially broken) version:

    pip install git+ssh://git@github.com/ludovicchabant/Wikked.git#egg=Wikked

Check that you have Wikked correctly installed by running:

    wk --help

You should see Wikked's command line help.

### From source

You can also use Wikked from source. It's recommended you use `virtualenv` for
this (see the [documentation][venv] for more info).  It would look something
like this:

    # Clone with either Mercurial or Git:
    hg clone ssh://hg@bitbucket.org/ludovicchabant/wikked
    git clone git@github.com:ludovicchabant/Wikked.git

    # Create and activate virtualenv, if you're on Bash
    virtualenv venv
    source venv/bin/activate

    # Install Wikked's requirements in venv
    pip install -r requirements.txt

    python wk.py --help


[venv]: http://www.virtualenv.org/


<a id="quickstart"></a>

## Quick start

Let's create a new wiki:

    wk init mywiki

This will create a new directory called `mywiki` with some basic files in it. It
will also initialize a [Mercurial][] repository in it. In the future, Wikked
will support other source control systems.

Now let's get in there and run it:

    cd mywiki
    wk runserver

You should now be able to open your favorite web browser and go to
`localhost:5000`. If you see the main page of your wiki, congratulations!
Otherwise, something went wrong. If you found a bug make sure to [file a
report][1] about it.

[1]: https://github.com/ludovicchabant/Wikked/issues


<a id="concepts"></a>

## Concepts

Wikked's data is entirely stored in text files on disk. All you ever need, or
should really care about, are those text files, and the source control
repository (which contains the history of those text files). Wikked may create
some other files -- cache files, indices, etc. -- but they can always be safely
deleted and re-created.


### The wiki folder

If you look at your new wiki, you should see a file called `Main page.md`, along
with a few hidden files and directories.

* Each page's text is stored in a file whose name is the name of the page. That
  name is important, since that's what you'll use for linking to it, and what
  will show up in that page's URL.
* Sub-directories also map to sub-folders in page names and URLs.
* There's a `.wiki` folder that was created in the wiki root. This folder is a
  cache, and can generally be safely deleted and re-created with the `wk reset`
  command. You may however have some local configuration file(s) here, which
  we'll talk about later.
* There's also some source control related files in there, like a `.hg` folder
  and `.hgignore` file in the case of Mercurial. Don't touch those, they're
  important (they store your pages' history). You can learn about them using the
  wonders of the internet.

> There's nothing preventing you from using accented or non-latin characters for
> a new page name, except for characters that would be invalid for a file name.
> However, please note that most revision control systems are going to behave
> badly if you'll be working with your repository on mixed systems (_i.e._
> Windows, Mac, Linux).


### General features

Wikked implements the usual wiki concepts of being able to edit pages, look at
their history and revert to previous revisions, and of course easily link to
other pages.

Wikked also supports the ability to include a page into another page, to assign
metadata (like categories) to pages, and to query pages based on that metadata.
So for example you can display a list of all pages under the category "_Witches
of Oz_".



<a id="syntax"></a>

## Wiki syntax

### Formatting

By default, Wikked will use [Markdown][] syntax, so that things like these:

    Ring around the rosie, a pocket full of *spears*! Thought you were pretty 
    foxy, didn't you? **Well!** The last to go will see the first three go 
    before her! _And your mangy little dog, too!_

...turn into this:

> Ring around the rosie, a pocket full of *spears*! Thought you were pretty 
> foxy, didn't you? **Well!** The last to go will see the first three go 
> before her! _And your mangy little dog, too!_

[markdown]: http://daringfireball.net/projects/markdown/

Page files that have a `.md` extension will be formatted using Markdown. Other
formats can be used, like Textile, but they won't have as much support, like for
instance giving a live preview of a page's text while you edit it.


### Links

Wikked also supports some simple wiki link syntax:

* Links are made with double square brackets: `[[Flying monkeys]]` will link to a
  page called `Flying monkeys.md`.
* Linking respects the "current directory", _i.e._ the directory of the current
  page. Linking to `Flying monkeys` from page `Villains/Wicked Witch` will lead
  you to `Villains/Flying monkeys`.
* To link using an "absolute" path, start with a slash: `[[/Villains/Flying
  monkeys]]`.
* To link to a page in the parent directory, use `..` like so:
  `[[../Munchkins]]`.
* You can quickly link to "child" pages by using `./`. For example, if you have
  a page called `/Munchkins` that links to `[[./Lollipop Guild]]`, it will lead
  to the page `/Munchkins/Lollipop Guild`.
* To give a different display name, write it before a vertical bar: `[[click
  here|Flying monkeys]]`.


### Metadata

To assign metadata to a page, use `{%raw%}{{name: value}}{%endraw%}`. For instance:

    {%raw%}
    {{category: Witches}}
    {%endraw%}

You may need to assign a long value, like the summary of the page. In that case,
you need to put the closing double curly braces by themselves on the last line:

    {%raw%}
    {{summary: This page is about flying monkeys, who serve
        the wicked witch of the west. They're not very bright
        but they are extremely loyal.
    }}
    {%endraw%}

> In this case, note that the carriage return to get to the closing braces won't
> be included in the metadata. If you want the metadata value to end with a
> carriage return, you'll need to add one, effectively leaving an empty line
> before the closing braces.


### Includes

The same syntax is used for including and querying pages. For instance, to
include the `Warning` page in another page:

    {%raw%}
    {{include: Warning}}
    {%endraw%}

You can supply a relative or absolute page name to the `include` meta. For
convenience, however, Wikked will first look in the `/Templates` folder for a
page of that name to include. If it doesn't find one, it will resolve the path
as usual.

The `include` meta accepts arguments. For example, the `City of Oz`
page may have this at the top:

    {%raw%}
    {{include: Warning
        |a work in progress
        |adding references
    }}
    {%endraw%}

Those arguments can then be used by the included `/Templates/Warning` page:

    {%raw%}
    WARNING! This page is {{__args[0]}}.
    You can help by {{__args[1]}}.
    {%endraw%}

This will make `City of Oz` print the following warning:

    WARNING! This page is a work in progress.
    You can help by adding references.

As you can see, arguments are passed as an array named `__args`, and this can be
inserted using double curly brackets. So {%raw%}`{{__args[0]}}`{%endraw%}
inserts the first passed argument, {%raw%}`{{__args[1]}}`{%endraw%} inserts the
second, and so on.

You can also pass arguments by name:

    {%raw%}
    {{include: Presentation
        |what=dog
        |nickname=Toto
    }}
    {%endraw%}

And use them by name in the included template:

    {%raw%}
    My {{what}} is called {{nickname}}.
    {%endraw%}

In reality, when included, a page's text will be processed through [Jinja2][]
templating so you can also use all kinds of fancy logic. For example, if you
want to support a default warning message, and an optional information message,
you can rewrite the `/Template/Warning` page like so:

    {%raw%}
    WARNING! This page is {{__args[0]|default('not ready')}}.
    {%if __args[1]%}You can help by {{__args[1]}}.{%endif%}
    {%endraw%}

For more information about what you can do, refer to the [Jinja2 templating
documentation][jinja2_tpl].

  [jinja2]: http://jinja.pocoo.org/
  [jinja2_tpl]: http://jinja.pocoo.org/docs/templates/

Pages with other pages included in them inherit the meta properties of the
included pages. You can tweak that behaviour:

* Meta properties that start with `__` (double underscore) will be "local" or
  "private" to that page, _i.e._ they won't be inherited by pages including the
  current one.
* Meta properties that start with `+` (plus sign) will only be "added" or
  "given" to pages including the current one, _i.e._ the current page won't have
  that property, but pages including it will.


### Queries

The query meta takes the name and value of the other meta to query on pages. So
for instance you can display a list of pages in the "_Witches_" category like
so:

    {%raw%}
    {{query: category=Witches}}
    {%endraw%}

This will print a bullet list of the matching pages' titles, with a link to
each.

You can customize how the list looks like by setting the following arguments:

* `__header`: The text to print before the list. The default is an empty line.
* `__item`: The text to print for each matching page. It defaults to: `*
  {%raw%}[[{{title}}|{{url}}]]{%endraw%}` (which means, as per Markdown
  formatting, that it will print a bulleted list of titles linking to each
  page).
* `__footer`: The text to print after the list. The default is an empty line.
* `__empty`: The text to show when no pages match the query. It defaults to a
  simple text saying that no page matched the query.

So for example, to display a description of each page next to its link, where the
description is taken from a `description` meta, you would do:

    {%raw%}
    {{query: category=Witches
        |__item=* [[{{title}}|{{url}}]]: {{description}}

    }}
    {%endraw%}

Note the extra empty line so that each item has a line return at the end...
otherwise, they would be all printed on the same line!

When a query parameter gets too complicated, you can store it in a separate meta
property, as long as that property starts with a double underscore:

    {%raw%}
    {{__queryitem: * [[{{title}}|{{url}}]]: {{description}} }}

    {{query: category=Witches|__item=__queryitem}}
    {%endraw%}

For extra long item templates, you can use a dedicated page. For example, here's
how you use the text in `/Templates/Witches Item` as the query item template:

    {%raw%}
    {{query: category=Witches|__item=[[/Templates/Witches Item]]}}
    {%endraw%}


<a id="config"></a>

## Configuration

Wikked can be configured with a few files:

* `.wikirc`: this file, located at the root of your wiki, can be submitted into
  revision control, so that various clones of the wiki have the same options
  where it makes sense.
* `.wiki/wikirc`: some options, however, don't have to be the same depending on
  where you run the wiki. This file is contained in the ignored-by-default
  `.wiki` folder, and as such is meant to store options valid only for a local
  installation.
* `.wiki/app.cfg`: Wikked runs on top of [Flask][]. This file, if it exists,
  will be passed on to Flask for more advanced configuration scenarios.
  
 [flask]: http://flask.pocoo.org/


The `wikirc` file is meant to be written with an INI-style format:

    [section1]
    foo=bar
    something=some other value

    [section2]
    blah=whatever


### Main options

The main Wikked options should be defined in a `[wiki]` section. Here are the
supported options:

* `main_page` (defaults to `Main page`): the name of the page that should be
  displayed when people visit the root URL of your wiki. Page names are case
  sensitive so watch out for the capitalization. 
* `templates_dir` (defaults to `Templates`): by default, the `include` feature
  (see above) will first look into a templates directory for a template of the
  given name. This is the name of that folder.
* `indexer` (defaults to `whoosh`): The full-text indexer to use. Only 2 indexers are currently
  supported, `whoosh` (for [Whoosh][]) and `elastic` (for [Elastic Search][elastic]).
* `database` (defaults to `sql`): The database system to use for the cache.
  Wikked currently only supports SQL.
* `database_url` (defaults to `sqlite:///%(root)s/.wiki/wiki.db`): the URL to
  pass to [SQLAlchemy][] for connecting to the database.

 [SQLAlchemy]: http://docs.sqlalchemy.org/en/latest/core/engines.html#database-urls 
 [whoosh]: https://bitbucket.org/mchaput/whoosh/wiki/Home
 [elastic]: http://www.elasticsearch.org/


### Permissions

The `wikirc` file can also be used to define user permissions. This is one of
the biggest limitations for scaling Wikked at the moment (see the
[limitations](#limitations) section).

The `[users]` section defines user accounts for the wiki:

    [users]
    dorothy=PASSWORD_HASH

The `PASSWORD_HASH` is, well, a password hash. You can generate one by using the
`wk newuser` command.

Once you have some users defined, you can give them some permissions, using the
`[permissions]` section. Supported settings are:

* `readers`: users able to read the wiki.
* `writers`: users able to edit the wiki.

Multiple usernames must be separated by a comma. You can also use `*` for "all
users", and `anonymous` for unauthenticated visitors.

The following example shows a wiki only accessible to registered users, and that
can only be edited by `dorothy` and `toto`:

    [permissions]
    readers = *
    writers = dorothy,toto

Those settings can also be overriden at the page level using the `readers` and
`writers` metadata. So you can still have a public landing page for the
previously mentioned private wiki by adding this to `Main page.md`:

    {%raw%}
    {{readers: *,anonymous}}
    {%endraw%}


<a id="deploy"></a>

## Deployment

Wikked runs by default with an "easy" configuration, _i.e._ something that will
"just work" when you play around locally. In this default setup, it uses
[SQLite][] for the cache, and [Whoosh][] for the full-text search, all running
in Flask's built-in server.

 [sqlite]: https://sqlite.org/

This technology stack works very well for running your wiki locally, or for 
private websites. It has some limitations, however:

* The `wk runserver` command runs the Flask development server, which you
  [shouldn't use in production][flaskdeploy]. You'll probably need to run Wikked
  inside a proper server instead.
* When a page has been edited, Wikked will immediately evaluate and reformat all
  pages that have a dependency on it. You probably want to have this done in the
  background instead.

In this chapter we'll therefore look at deployment options, and follow-up with
some more advanced configurations for those with special requirements.

 [flaskdeploy]: http://flask.pocoo.org/docs/deploying/
    

### Apache and WSGI

A simple way to run Wikked on a production server is to use [Apache][] with
[`mod_wsgi`][wsgi]. For a proper introduction to the matter, you can see
[Flask's documentation on the subject][flask_wsgi]. Otherwise, you can probably
reuse the following examples.

 [apache]: https://httpd.apache.org/
 [wsgi]: http://code.google.com/p/modwsgi/
 [flask_wsgi]: http://flask.pocoo.org/docs/deploying/mod_wsgi/

The first thing is to create a `.wsgi` file somewhere on your server. You only
need to create the Wikked WSGI app in it, and optionally activate your
`virtualenv` if you're using that:

    # Activate your virtualenv
    activate_this = '/path/to/venv/bin/activate_this.py'
    execfile(activate_this, dict(__file__=activate_this))

    # Get the Wikked WSGI app
    from wikked.wsgiutil import get_wsgi_app
    application = get_wsgi_app('/path/to/your/wiki/root')

The second thing to do is to add a new virtual host to your Apache
configuration. The [Flask documentation][flask_wsgi] shows an example that you
should be able to use directly, although you'll also need to tell Apache where
to serve some static files: Wikked's static files (Javascript, CSS, icons,
etc.), and you own wiki's files (your pictures and other attachments). This
means your Apache configuration will look like this in the end:

    <VirtualHost *:80>
        ServerName yourwikidomain.com

        WSGIDaemonProcess yourwiki user=user1 group=group1 threads=5
        WSGIScriptAlias / /path/to/your/wsgi/file.wsgi

        DocumentRoot /path/to/your/wiki/_files
        Alias /static/ /path/to/wikked/static/

        <Directory /path/to/your/wiki>
            WSGIProcessGroup yourwiki
            WSGIApplicationGroup %{GLOBAL}
            Order deny,allow
            Allow from all
        </Directory>
    </VirtualHost>

> You will have to create the `_files` directory in your wiki before
> reloading Apache, otherwise it may complain about it.
> 
> Also, the path to Wikked's `static` directory is going to point directly into
> your installed Wikked package. So if you installed it with `virtualenv`, it
> would be something like:
> `/path/to/your/wiki/venv/lib/python/site-packages/wikked/static`.


### Background updates

The second thing to do is to enable background wiki updates. Good news: they're
already enabled if you used the `get_wsgi_app` function from the previous
section (you can disable it by passing `async_update=False` if you really need
to).

> If you want to use background updates locally, you can do `wk runserver
> --usetasks`.

However, you'll still need to run a separate process that, well, runs those
updates in the background. To do this:

    cd /path/to/my/wiki
    wk runtasks

> The background task handling is done with [Celery][]. By default, Wikked will
> use the [SQLAlchemy transport][celerysqlite].

 [celery]: http://www.celeryproject.org/
 [celerysqlite]: http://docs.celeryproject.org/en/latest/getting-started/brokers/sqlalchemy.html


### Backend options

**This is for advanced use only**

If you want to use a different storage than SQLite, set the `database_url`
setting in your `wikirc` to an [SQLAlchemy-supported database URL][SQLAlchemy].
For instance, if you're using MySQL with `pymsql` installed:

    [wiki]
    database_url=mysql+pymysql://username:password123@localhost/db_name

 [sqlalchemy]: http://docs.sqlalchemy.org/en/rel_0_9/core/engines.html#database-urls

> Note that you'll have to install the appropriate SQL layer. For instance: `pip
> install pymsql`. You will also obviously need to setup and configure your SQL
> server.


If Whoosh is also not suited to your needs, you can use [Elastic
Search][elastic] instead:

    [wiki]
    indexer=elastic

You'll obviously have to install and run Elastic Search.

 [elastic]: http://www.elasticsearch.org/



<a id="limitations"></a>

## Limitations

Wikked was written mainly for a small group of editors in mind. It's especially
well suited for a personal digital notebook, a private family documents
repository, or a wiki for a small team of developers.

The main limitation of Wikked comes into play when you increase the number of
contributors -- *not* when you increase the number of visitors. Once the website
is cached, all requests are done against the SQL database, and search is done
through the indexer. This means you can scale quite well as long as you have the
appropriate backend (and as long as I don't write anything stupid in the code).

However, user accounts are stored in a text file, and must be added by hand by
an administrator, so it's impossible to scale this up to hundreds or thousands
of users. You could probably improve this by adding a different user account
backend, but when those users start editing pages, each edit must write to a
separate file on disk, and be committed to a source control repository, and this
will probably prove to be a bottleneck anyway at some point.

In summary: Wikked should be able to handle lots of visitors, but not too
many contributors.


<a id="support"></a>

## Support

If you need assistance with Wikked, [contact me directly][me] or report an issue
on the [GitHub bug tracker][bugs].

 [me]: http://ludovic.chabant.com
 [bugs]: https://github.com/ludovicchabant/Wikked/issues

