# Eyefi PHP Library
This is a PHP Class based on https://github.com/eyefi/eyefi-js to access the Eyefi´s API

## NOTE: This is very much "work in progress"...

# Usage
```php
require_once(dirname(__FILE__) . "/EyeFiClient.php");
$eyefi = new EyeFiClient(array('access_token' => "YOUR_TOKEN"), 'es');
```
# Objects and Methods
```php
$eyefi->GetUserEvents();
$eyefi->GetUserEvents($eventId);
$eyefi->GetUserEventsFiles($eventId);
$eyefi->UpdateUserEvents($eventId, $name);
$eyefi->GetAlbums();
$eyefi->GetAlbums($albumId);
$eyefi->NewAlbum($name);
$eyefi->UpdateAlbum($albumId, $name, $privacy);
$eyefi->DeleteAlbum($albumId);
$eyefi->AddFilestoAlbum($albumId, $files);
$eyefi->GetFiles();
$eyefi->GetFiles($fileId);
$eyefi->NewFile($file);
$eyefi->DeleteFile($fileId);
$eyefi->AddFileTag($fileId, $tags);
$eyefi->GetFileTags($fileId);
$eyefi->RemoveFileTag($fileId, $tagId);
$eyefi->SearchFile($favorite, $edited, $raw, $in_trash, $has_geodata, $geo_lat, $geo_lon, $geo_distance, $album_ids, $event_ids, $tag_ids, $camera, $date_from, $date_to, $created_from, $created_to, $page, $per_page, $sort, $order);
```

# License
Copyright 2015 David Ayala

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
