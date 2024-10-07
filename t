#!/bin/bash

# Check if session name is provided
if [ -z "$1" ]; then
  echo "Usage: $0 <session-name>"
  exit 1
fi

touch ~/.t-prev-sess
touch ~/.t-curr-sess

if [ ! -z "$TMUX" ]; then
    if [ $1 = "-" ]; then
        sess=$(cat ~/.t-prev-sess 2>/dev/null)
        if [ -z $sess ]; then
            echo "No previous session"; exit 1
        fi
    else
        sess=$1
    fi
    tmux switch-client -t $sess
    cat ~/.t-curr-sess > ~/.t-prev-sess
    echo $sess > ~/.t-curr-sess
    exit 0
fi



SESSION_NAME=$1
SESSION_DIR="$HOME/Documents/$SESSION_NAME"

# Check if session already exists
tmux has-session -t "$SESSION_NAME" 2>/dev/null

if [ $? != 0 ]; then

  cd $SESSION_DIR

  # Create a new session
  tmux new-session -d -s "$SESSION_NAME"

  # Split the window into two panes (top and bottom)
  tmux split-window -v -t "$SESSION_NAME"

  # Attach to the newly created session
  tmux attach-session -t "$SESSION_NAME"
  cat ~/.t-curr-sess > ~/.t-prev-sess
else
  # Session already exists, just attach to it
  tmux attach-session -t "$SESSION_NAME"
  cat ~/.t-curr-sess > ~/.t-prev-sess
fi
