---
title: Wikked
layout: wikked
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

Check that you have Wikked correctly installed by running:

    wk --help

You should see Wikked's command line help.

### From source

You can also use Wikked from source. It's recommended you use `virtualenv` for
this:

    <clone the repo with Git or Mercurial>
    <initialize/activate virtualenv>
    pip install -r requirements.txt
    python wk.py --help


<a id="quickstart"></a>

## Quickstart

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

Wikked's data is entirely stored in text files on disk. If you look at your
newly created wiki, you should see a file called `Main page.md`, along with a
few hidden files and directories.

* Each page's text is stored in a file whose name is the name of the page, which
  is also the URL of the page. So a page named `Dorothy.md` will be linked to
  with `[[Dorothy]]` (see the [syntax section](#syntax) for more information
  on linking).
* Sub-directories also map to sub-folders in page names and URLs. So a file
  whose relative path is `Geography/Oz.md` will be linked to with
  `[[Geography/Oz]]`.
* There's a `.wiki` folder that was created in the wiki root. This folder is a
  cache, and can generally be safely deleted and re-created with the `wk reset`
  command. You may however have some local configuration file(s) here, which
  we'll talk about later.
* There's also some source control related files in there, like a `.hg` folder
  and `.hgignore` file in the case of Mercurial. Don't touch those, they're
  important. You can learn about them using the wonders of the internet.

Apart from this, Wikked uses the usual wiki concepts of being able to edit
pages, look at their history and revert to previous revisions, and of course
easily link to other pages.

Wikked also supports the ability to include a page into another page, to assign
metadata (like categories) to pages, and to query pages based on that metadata.
So for example you can display a list of all pages under the category "_Witches
of Oz_".


<a id="editing"></a>

## Editing Pages

You can edit any page by clicking the "_edit_" link in the top navigation bar.
Once you're done editing, you hit "_save_". If you're not happy with your edits,
you can instead click on "_cancel_".

> You can optionally specify an author and a message for the commit to the
> backend storage. These will show up in the history of the page.

You can also create a new page with the "_new page_" button on the top right
(also in the navigation bar). You will be able to choose a title for the page,
which will map directly to the name of the new text file. The rest is similar to
editing an existing page.

> There's nothing preventing you from using accented or non-latin characters for
> a new page name, except for characters that would be invalid for a file name.
> However, please note that most revision control systems are going to behave
> badly if you'll be working with your repository on mixed systems (_i.e._
> Windows, Mac, Linux).


<a id="syntax"></a>

## Wiki syntax

### Markdown

By default, Wikked will use [Markdown][] syntax, so that things like these:

    Ring around the rosie, a pocket full of *spears*! Thought you were pretty 
    foxy, didn't you? **Well!** The last to go will see the first three go 
    before her! _And your mangy little dog, too!_

...turn into this:

> Ring around the rosie, a pocket full of *spears*! Thought you were pretty 
> foxy, didn't you? **Well!** The last to go will see the first three go 
> before her! _And your mangy little dog, too!_

[markdown]: http://daringfireball.net/projects/markdown/


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
inserted using double curly brackets. So `{{__args[0]}}` inserts the first
passed argument, `{{__args[1]}}` inserts the second, and so on.

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
