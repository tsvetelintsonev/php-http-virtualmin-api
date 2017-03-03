<?php
return [
    /*
     ***********************************************************************************
     * DNS configuration
     ***********************************************************************************
     |
     */
    "defaultTtl" => "6h",

    /*
     ***********************************************************************************
     * PHP configuration
     ***********************************************************************************
     |
     */
    "userModifiableIniVars" => [
        "max_execution_time",
        "display_errors"
    ],

    /*
     ***********************************************************************************
     * Script installers configuration
     ***********************************************************************************
     |
     */
    "scripts" => [
        "supportedScripts" => [
            "wordpress",
            "phpmyadmin"
        ],
        "cms" => [
            "wordpress" => [
                // scripts short name
                "scriptName" => "wordpress",
                "dir" => "wordpress",
                "dbName" => "wordpress"
            ]
        ],
        "dbPanels" => [
            "phpmyadmin" => [
                "scriptName" => "phpmyadmin",
                "dir" => "rdbms"
            ]
        ]
    ]
];

