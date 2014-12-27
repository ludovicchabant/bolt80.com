---
title: 
layout: allpaths
---

*Allpaths* is a [Mercurial][] extension that lets you execute commands on
multiple paths, _i.e_ multiple remote repositories.


## Installation

Grab it from [BitBucket][repo] and store it some place nice. Then, edit your
`.hgrc` file to activate the extension:

    [extensions]
    allpaths = [path to]/allpaths.py


## Usage 

For now, there is only support for the push command:

    $ hg pushall

This will push to all paths specified in the `[paths]` config section.

You can also provide the name of a different section:

    $ hg pushall -g publish

This will push to all paths specified in the `[publish]` config section, which should look like this:

    [publish]
    bitbucket = ssh://hg@bitbucket.org/ludovicchabant/piecrust
    github = git+ssh://git@github.com:ludovicchabant/PieCrust.git
    other = ssh://my@own/server
    local = /some/other/place

You can also provide standard push options:

    $ hg pushall -b branch


  [mercurial]: http://mercurial.selenic.com
  [repo]: https://bitbucket.org/ludovicchabant/allpaths

