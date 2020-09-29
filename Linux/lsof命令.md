lsof | grep /mnt/homework | grep deleted | awk '{print $2}' | xargs kill -9
