# Confirm all packages are up to date
sudo yum update -y

# Install stack
sudo amazon-linux-extras install -y lamp-mariadb10.2-php7.2 php7.2
sudo yum install -y httpd mariadb-server

# Start Apache
sudo systemctl start httpd
sudo systemctl enable httpd

# Manage permissions on Apache installation directories
sudo usermod -a -G apache ec2-user
sudo chown -R ec2-user:apache /var/www
sudo chmod 2775 /var/www && find /var/www -type d -exec sudo chmod 2775 {} \;
find /var/www -type f -exec sudo chmod 0664 {} \;
