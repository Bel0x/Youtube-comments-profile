<!-- <?php
// Generate a random seed
$seed = mt_rand();

// Specify the avatar style (e.g., "male", "female", "identicon", "monsters")
$style = "male"; // You can change the style according to your preference

// Build the API URL
$url = "https://avatars.dicebear.com/api/{$style}/{$seed}.svg";

// Output the avatar image
echo '<img src="' . $url . '" alt="Cool Avatar">';
?> -->
<!-- <?php
// Make a request to the Random User Generator API
$url = 'https://randomuser.me/api/';
$response = file_get_contents($url);

// Parse the JSON response
$data = json_decode($response, true);

// Retrieve the profile picture URL
$profilePictureUrl = $data['results'][0]['picture']['large'];

// Output the profile picture
echo '<img src="' . $profilePictureUrl . '" alt="Profile Picture">';
?>
 -->
<!--  <?php
// Generate a random seed (e.g., username or email)
$seed = uniqid();

// Construct the Robohash API URL
$apiUrl = 'https://robohash.org/' . $seed;

// Output the random cool image
echo '<img src="' . $apiUrl . '" alt="Profile Image">';
?>
 -->
<!--  <?php
// Generate a random identifier for the profile
$identifier = uniqid();

// Make a request to the Avatar API for a random cool avatar image
$url = 'https://avatars.dicebear.com/api/avataaars/' . $identifier . '.png';

// Output the random cool avatar image
echo '<img src="' . $url . '" alt="Cool Avatar Image">';
?>
 -->

<?php

// Set your YouTube Data API key
$apiKey = 'AIzaSyAXdwauJ5prkk3_V5ErrJ0C7xurCfP5D8s';

// YouTube video ID
$videoId = '9xkEHTqgr7o';

// YouTube API endpoint
$apiUrl = 'https://www.googleapis.com/youtube/v3/commentThreads';
// Set the parameters
$params = [
    'part' => 'snippet',
    'videoId' => $videoId,
    'key' => $apiKey,
    'maxResults' => 100, // Set the maximum number of comments to retrieve per page
];

// Array to store profile image URLs
$profileImages = [];

// Function to retrieve comments from a specific page token
function retrieveComments($pageToken = null)
{
    global $apiUrl, $params, $profileImages;

    if ($pageToken) {
        $params['pageToken'] = $pageToken;
    }

    // Build the API request URL
    $requestUrl = $apiUrl . '?' . http_build_query($params);

    // Send a GET request to the API endpoint
    $curl = curl_init($requestUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    // Decode the response
    $data = json_decode($response, true);

    // Check if the request was successful
    if (isset($data['items']) && !empty($data['items'])) {
        // Extract profile image URLs from comments
        foreach ($data['items'] as $item) {
            $profileImageUrl = $item['snippet']['topLevelComment']['snippet']['authorProfileImageUrl'];
            if (!empty($profileImageUrl)) {
                // Add unique profile image URLs to the array
                if (!in_array($profileImageUrl, $profileImages)) {
                    $profileImages[] = $profileImageUrl;
                }
            }
        }

        // Check if there are more pages
        if (isset($data['nextPageToken'])) {
            // Retrieve comments from the next page recursively
            retrieveComments($data['nextPageToken']);
        }
    }
}

// Retrieve comments from all pages
retrieveComments();

// Calculate the number of comments
$commentCount = count($profileImages);

// Define the number of columns
$columns = 10; // Adjust the number of columns as needed

// Calculate the number of rows
$rows = ceil($commentCount / $columns);

// Output the comment count and profile images in rows and columns
echo '<h3>Number of Comments: ' . $commentCount . '</h3>';

if ($commentCount > 0) {
    echo '<table>';
    for ($i = 0; $i < $rows; $i++) {
        echo '<tr>';
        for ($j = 0; $j < $columns; $j++) {
            $index = $i * $columns + $j;
            if ($index < $commentCount) {
                echo '<td><img src="' . $profileImages[$index] . '" alt="Profile Image"></td>';
            }
        }
        echo '</tr>';
    }
    echo '</table>';
} else {
    // No comments with profile images found
    echo 'No profile images found in the comments.';
}
?>