#!/bin/bash

# Check if session name is provided
if [ -z "$1" ]; then
  echo "Usage: $0 <session-name>"
  exit 1
fi

if [ ! -z "$TMUX" ]; then
    tmux switch-client -t $1
    exit 0
fi

SESSION_NAME=$1
SESSION_DIR="$HOME/Documents/$SESSION_NAME"

# Check if session already exists
tmux has-session -t "$SESSION_NAME" 2>/dev/null

if [ $? != 0 ]; then
  # Create a new session
  tmux new-session -d -s "$SESSION_NAME"

  # Check if the directory exists, cd to it if it does
  if [ -d "$SESSION_DIR" ]; then
    tmux send-keys -t "$SESSION_NAME" "cd $SESSION_DIR" C-m
  fi

  # Split the window into two panes (top and bottom)
  tmux split-window -v -t "$SESSION_NAME"

  # Set both panes to cd to the same directory if it exists
  if [ -d "$SESSION_DIR" ]; then
    tmux send-keys -t "$SESSION_NAME:0.1" "cd $SESSION_DIR" C-m
  fi

  # Attach to the newly created session
  tmux attach-session -t "$SESSION_NAME"
else
  # Session already exists, just attach to it
  tmux attach-session -t "$SESSION_NAME"
fi
