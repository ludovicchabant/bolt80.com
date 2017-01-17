---
title:
layout: september
---

September is a program that takes a source controlled project, goes back to
specific points in that project's timeline, and does something.

![This is September]({{assets.september}})

This is typically useful for generating documentation for each released version
of a project, but it can do other stuff like report on how the code evolved over
time in terms of amount or complexity.


## Usage

You simply run it by pointing at your repo:

    python september.py /path/to/repo --command "foo bar"

By default, this will clone your repository into a temporary directory (which
you can override with the `--tmp-dir` argument), update back to every tag, and
run `foo bar` each time at the root of the clone.

If you already ran September previously, however, it will only run the command
on any new/changed tag. You can see what September remembers by using the
`--status` argument.

September will guess what kind of repository you're giving it, and do the
appropriate things to clone and update it. Right now Git and Mercurial are
supported.


## Configuration file

You can pass a configuration file to September with the `--config` argument. If
you have a `.september.cfg` file at the root of your repository, it will pick it
up automatically otherwise.

The configuration file must specify settings in a `september` section:

    [september]
    command = echo "Processing tag %(tag)s..."
    use_shell = 1

Supported configuration settings include:

* `command`: The command to run. Use `%(tag)s` to insert the name of current tag
  in the command.
* `use_shell`: Whether the command should be passed to a shell (_e.g._ if using
  shell commands like `ls` or `echo`, or using pipes or redirection).
* `first_tag`: Don't update to tags older than the tag specified as the first
  tag.
* `tag_pattern`: A regular expression that defines which tags to consider. Any
  tag that _doesn't_ match the pattern will be ignored.

