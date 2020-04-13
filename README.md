# bot
sudo docker build -t bot . <br/>
sudo docker run -d -v /var/www/html:/var/www/html -p 22:22 -p 8080:80 bot <br/>
sudo docker ps <br/>
sudo docker stop imagID <br/>
