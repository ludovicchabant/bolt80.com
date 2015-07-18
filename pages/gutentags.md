---
title: 
layout: gutentags
---

<a id="overview"></a>

Gutentags is a plugin that takes care of the much needed management of tags
files in [Vim][]. It will (re)generate tag files as you work while staying
completely out of your way. It will even do its best to keep those tag files
out of your way too. It has no dependencies and just works.

In order to generate tag files, Gutentags will have to figure out what's in
your project. To do this, it will locate well-known project root markers like
SCM folders (.git, .hg, etc.), any custom markers you define (with
`gutentags_project_root`), and even things you may have defined already with
other plugins, like CtrlP.

If the current file you're editing is found to be in such a project, Gutentags
will make sure the tag file for that project is up to date. Then, as you work
in files in that project, it will partially re-generate the tag file. Every
time you save, it will silently, in the background, update the tags for that
file.

Usually, ctags can only append tags to an existing tag file, so Gutentags
removes the tags for the current file first, to make sure the tag file is
always consistent with the source code.

Also, Gutentags is clever enough to not stumble upon itself by triggering
multiple ctags processes if you save files to fast, or your project is really
big.


<a id="installation"></a>
## Installation

The recommended method to install Gutentags is to use [Pathogen][]:

    cd ~/.vim/bundle
    hg clone https://bitbucket.org/ludovicchabant/vim-gutentags

You can then update the help tags with `:call pathogen#helptags()` and browse
Gutentags' help pages with `:help gutentags`.

You can alternatively [download the latest snapshot][download] as a ZIP archive
and extract it yourself in the `bundle` directory, or any other place where Vim
will pick it up.

The source code for Lawrencium is available on [Github][] and [Bitbucket][],
depending on which, between Git or Mercurial, is your cup of tea.

To generate the tags files, you'll also need some kind of `ctags` tool. These
days there's really no need to go look beyond [Exhuberant Ctags][ctags].


<a id="quickstart"></a>
## Quick Start

After both Gutentags and Ctags are installed, the plugin should start working for
you automatically. If you open anything from inside a Git or Mercurial
repository, it will recognize it and start generating tags in the background.

To know when Gutentags is generating tags, add this to your `vimrc`:

    set statusline+=%{gutentags#statusline()}

This will print the string "TAGS" in your status-line when Gutentags is
generating things in the background.

If you want to generate tags files for other things than usual source-control
repositories (Git, Mercurial, Bazaar, Darcs), you can define the
`g:gutentags_project_root` variable in your `vimrc`. For instance:

    let g:gutentags_project_root = ['Makefile']

This will activate Gutentags when opening a file that's somewhere under a
directory that contains a `Makefile` file or folder.


<a id="customization"></a>
## Customization

### Excluding files

If you want to exclude things on a per-project basis, or otherwise customize
`ctags` parameters for a given project, you can place a `.gutctags` file at the
root of your project. It's a standard `ctags` options file, and will be passed
to `ctags` via the `--options` parameter.

Note that your `wildignore` patterns are already passed as patterns to ignore,
and of course your home `~/.ctags` is automatically used by `ctags`.

### Hiding tags files

If you don't want the tags files to pollute your projects, you can define a
`g:gutentags_cache_dir` variable in your `vimrc`. Tags files will go in this
folder instead of each project directory.


<a id="more"></a>
## More

There are more advanced options available. See `:help gutentags` for more
information. You can also post questions and bug reports either in the [Github
issue tracker][2] or the [Bitbucket issue tracker][1].


  [vim]: http://www.vim.org
  [ctags]: http://ctags.sourceforge.net/
  [pathogen]: https://github.com/tpope/vim-pathogen
  [download]: https://bitbucket.org/ludovicchabant/vim-gutentags/get/default.zip
  [github]: https://github.com/ludovicchabant/vim-gutentags
  [bitbucket]: https://bitbucket.org/ludovicchabant/vim-gutentags/
  [1]: https://bitbucket.org/ludovicchabant/vim-gutentags/issues
  [2]: https://github.com/ludovicchabant/vim-gutentags/issues

