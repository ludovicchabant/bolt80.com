---
title: 
layout: saturdaymorning
---

<a id="overview"></a>

At its core, SaturdayMorning is a little script that moves files from one directory to another based on some kind of schedule -- daily, every tuesdays, etc.

The primary use-case, however, is to handle TV show episodes. If you put your video files in a directory that's not accessible by whatever player you use (like Plex), SaturdayMorning can "release" them on a schedule, like once a week, like an actual TV show.

Why would you want to do that? Well, you probably don't. But maybe you do. You'll know.


<a id="quickstart"></a>

## Quickstart

You can install SaturdayMorning with `pip`

```
$ pip install saturdaymorning
```


You can then run it on the command line with the `saturdaymorning` command. You pass the source and destination directory.

The source directory must contain sub-directories with, at some level, a `satmonrc` file in them. This is the SaturdayMorning configuration file that says what and when to move files.

When a file is moved, it will have, in the destination directory, the same relative path as it has in the source directory. By the time SaturdayMorning has moved all the files, the destination directory will be exactly the same as what the source directory was before the first invocation.


<a id="configuration"></a>

## Configuration

The `.satmonrc` file tells SaturdayMorning what files to move when. It uses the INI file format:

```
[all]
schedule = sunday
move = siblings
```


This will move any "*siblings*" of the configuration file, but only on Sundays. In this situation, a sibling is a file in the same directory as the configuration file.

The following configuration settings are required:

* `schedule` When to move files inside, or under, this configuration file's directory. Valid values are `daily` `weekday` or the name some specific day (`monday` `tuesday` etc.).


The following configuration settings are optional:

* `move` (defaults to `nephews` Specifies what files to move. Values can be:

  * `siblings` files in the same directory as the configuration file will be moved.
  * `nephews` files inside sub-directories of the configuration file's directory will be moved.


