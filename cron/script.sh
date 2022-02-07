docker exec www_annuaire php /var/www/annuary/bin/console app:delete:unverified-users
echo "$(date): executed script" >> /var/log/cron.log 2>&1