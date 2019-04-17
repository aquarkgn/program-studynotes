#!/bin/bash

# Start haproxy
/etc/init.d/haproxy stop
/etc/init.d/haproxy start
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start haproxy: $status"
  exit $status
fi

# Start keepalived
/etc/init.d/keepalived stop
/etc/init.d/keepalived start
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start keepalived: $status"
  exit $status
fi

# 进入shell
/bin/bash