#!/bin/bash

echo -e "============ Git Status ==============="

git status

echo -e "Do you want to git add all?(Y/n)"
read -p "Answer: " add

if [[ $add =~ ^[Yy]$ ]]
then
  git add .
  git status
else
  exit 0
fi

echo -e "Do you want to commit it?(Y/n)"
read -p "Answer: " commit

if [[ $commit =~ ^[Yy]$ ]]
then
  read -p "Commit message: " message

  git commit -m "$message"
  git status
else
  exit 0
fi

echo -e "Do you want to push now?(Y/n)"
read -p "Answer: " push

# checks if branch has something pending
function parse_git_dirty() {
  git diff --quiet --ignore-submodules HEAD 2>/dev/null; [ $? -eq 1 ] && echo "*"
}

# gets the current git branch
function parse_git_branch() {
  git branch --no-color 2> /dev/null | sed -e '/^[^*]/d' -e "s/* \(.*\)/\1$(parse_git_dirty)/"
}

if [[ $push =~ ^[Yy]$ ]]
then
  CURRENT_BRANCH=$(parse_git_branch)
  read -p "Branch Name: ($CURRENT_BRANCH) - " branch

  if [[ -z $branch ]]
  then
    echo -e "Pushing $CURRENT_BRANCH ..."
    git push origin "$CURRENT_BRANCH"
  else
    echo -e "Pushing $branch ..."
    git push origin "$branch"
  fi
else
  exit 0
fi