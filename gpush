#!/bin/bash
git add -u
git commit -m ${1:-updates}
git push origin $(git branch --show-current)
git status -s
