<?php
return [
    'elfinder' => [
        'root_options' => array (
            'defaults'   => array (
                'read' => true,  // read image folder
                'write' => true, // add image to folder
                'locked' => true // deny edit, remove image exist
            ),
            'uploadAllow'   => array('image'), // Mimetype `image` and `text/plain` allowed to upload
            'uploadOrder'   => array('allow', 'deny'),      // allowed Mimetype `image` and `text/plain` only
            'uploadMaxSize' => '10M'
        ),
        'dir' => ['storage/images']
    ]
];