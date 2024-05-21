
# MusicQueueBuddy

MusicQueueBuddy is a web application designed to enhance group music experiences, especially during trips. With this web app, you can log in with your Spotify account, view your current queue, and add songs to it. The main idea is to allow friends to access the web page and add songs to the queue without needing to constantly manage the music from a mobile device.

## Features

- **Spotify Authentication**: Securely log in with your Spotify account using OAuth.
- **View Queue**: See the current songs in your Spotify queue.
- **Add Songs**: Search for songs and add them to your queue directly from the web app.
- **Collaborative**: Designed for group usage, perfect for trips and gatherings.
- **Responsive Design**: Although the design is basic, the app is somewhat responsive, making it usable on mobile devices.
- **Easy Sharing**: To allow others to add songs, simply share the web page link after logging in. The username is passed via GET parameters.

## Important Notice

This project is a prototype and has not been thoroughly tested for security vulnerabilities. It is not recommended to deploy this application to the web for public use. If you decide to deploy it, you do so at your own risk and responsibility. Ensure you take necessary security measures if you choose to make it publicly accessible.

I am aware that passing the username via GET allows anyone to access your account, but since this web app is intended for use among friends and not meant for online deployment, I have not addressed this issue. However, any improvements are welcome.

## Getting Started

To get a local copy up and running, follow these steps.

### Prerequisites

- Docker
- Docker Compose
- A Spotify Developer account to obtain your Client ID and Client Secret

### Installation

1. **Clone the repo**
   ```sh
   git clone git@github.com:02oigoa/MusicQueueBuddy.git
   cd MusicQueueBuddy
   ```

2. **Set up environment variables**

   Create a `.env` file in the root directory with the following content:
   ```env
   SPOTIFY_CLIENT_ID=your_spotify_client_id
   SPOTIFY_CLIENT_SECRET=your_spotify_client_secret
   SPOTIFY_REDIRECT_URI=your_redirect_uri
   ```

3. **Configure placeholders**

   In the following files, replace the placeholders with your actual values:
   
   - **docker-compose.yml**
     - Ports
     - Database configuration
     
   - **Dockerfile**
     - Web port
     
   - **login.php**
     - `$client_id` (replace 'your_id')
     - `$redirect_uri` (from your Spotify API project)
     
   - **callback.php**
     - `$client_id` (replace 'your_id')
     - `$client_secret` (replace 'your_secret')
     - `$redirect_uri` (from your Spotify API project)
     
   - **ink/konexioa.php**
     - Database configuration from `docker-compose.yml`
     
   - **functions/funtzioak.php**
     - `$client_id` (replace 'your_id')
     - `$client_secret` (replace 'your_secret')

4. **Build and run the application with Docker Compose**
   ```sh
   docker-compose up --build
   ```

5. **Access the web application**

   Open your browser and navigate to `http://localhost:8082`.

## Usage

- **Log in with Spotify**: Click the login button to authenticate.
- **View and Manage Queue**: See your current queue and add new songs.
- **Share the Link**: Share the web page link after logging in so others can add songs.

## Contributing

Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

Feel free to make any changes and pull requests you want. Please have a bit of mercy on me as I know the code is a mess, but programming is something I enjoy doing in my free time even though I haven't formally learned it.

## Contact

Oier Igoa - [@ig0ita](https://twitter.com/ig0ita) - 02oierigoa@gmail.com

Project Link: [https://github.com/02oigoa/MusicQueueBuddy](https://github.com/02oigoa/MusicQueueBuddy)
