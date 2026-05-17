#!/bin/bash
while true; do
  php artisan trader:process-pending-approvals
  sleep 60
done
