# bot
sudo docker build -t bot .
sudo docker run -d -v /var/www/html:/var/www/html -p 22:22 -p 8080:80 bot
sudo docker ps
sudo docker stop imagID
