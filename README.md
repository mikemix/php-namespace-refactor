In need of namespace refactor in your PHP project? I sometimes reorganize my classess between directories if I decide new directory structure is needed. I don't want to change the namespaces and imports in all files though, because I'm lazy. Here comes this tool.

Mount your source directory to the `/refactor` inside the container, provide the PSR-4 namespace for the directory, eg.:

    docker run -it --rm -v $PWD/src:/refactor App

This will run in dry-run mode, nothing will get changed. To write the changes, add the `--dry-run=0` option:

    docker run -it --rm -v $PWD/src:/refactor App --dry-run=0
