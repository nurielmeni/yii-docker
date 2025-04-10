# 1. Clone the yii2-docker repository
# Navigate to the directory where you want to store your projects
cd /path/to/your/projects

# Clone the official repository
git clone https://github.com/yiisoft/yii2-docker.git yii2-basic-app

# Navigate into the cloned directory
cd yii2-basic-app

# --------------------------------------------------

# 2. Configure the Environment for PHP 7.4
# Copy the example environment file
cp .env.example .env

# Edit the .env file using your preferred text editor (nano, vim, vscode, etc.)
# For example:
# nano .env

# Find the line starting with PHP_VERSION and change it to 7.4
# Ensure the line looks like this:
# PHP_VERSION=7.4

# Save and close the .env file.

# --------------------------------------------------

# 3. Build the Docker Images
# This command builds the images defined in docker-compose.yml, including the PHP 7.4 image.
# This might take a few minutes, especially the first time.
docker-compose build

# --------------------------------------------------

# 4. Initialize the Yii2 Basic Application Template
# Use docker-compose run to execute the composer command inside a temporary PHP container.
# This command downloads the Yii2 basic template into the `/app` directory inside the container,
# which is mapped to the `./yii-app` directory on your host machine.
docker-compose run --rm php composer create-project --prefer-dist yiisoft/yii2-app-basic /app

# If the `./yii-app` directory didn't exist, the above command creates it and populates it.

# --------------------------------------------------

# 5. Start the Docker Services
# Start the containers (php, nginx, db, etc.) in detached mode (background).
docker-compose up -d

# --------------------------------------------------

# 6. Adjust Directory Permissions (If Necessary)
# Sometimes, files created by composer (running as root inside the container)
# might not be writable by the web server user (www-data).
# Fix permissions for runtime and assets directories.
docker-compose exec php chown -R www-data:www-data /app/runtime /app/web/assets

# --------------------------------------------------

# 7. Verify the Setup
# Open your web browser and navigate to:
# http://localhost:8000
# (Or the IP address of your Docker host if not running locally, check DOCKER_HOST_IP in .env)

# You should see the Yii2 "Congratulations!" welcome page.

# --------------------------------------------------

# --- Development Workflow ---

# Your Yii2 application code is located in the `./yii-app` directory on your host machine.
# You can edit these files directly using your IDE.

# To run Yii console commands (e.g., migrations):
# docker-compose exec php yii <command>
# Example: docker-compose exec php yii migrate

# To view logs:
# docker-compose logs -f <service_name>
# Example: docker-compose logs -f php
# Example: docker-compose logs -f nginx

# To stop the containers:
# docker-compose down

# To stop and remove volumes (like database data):
# docker-compose down -v