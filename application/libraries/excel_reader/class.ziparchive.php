<?php

class ZipImages
{
    private $folder;
    private $url;
    private $html;
    private $fileName;
    private $status;

    public function __construct($url)
    {
        $this->url = $url;
        $this->html = file_get_contents($this->url);
        $this->setFolder();
    }

    public function setFolder($folder = "image")
    {
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        $this->folder = $folder;
    }

    public function setFileName($name = "zipImages")
    {
        $this->fileName = $name;
    }

    public function domCrawler()
    {
        $crawler = new Crawler($this->html);
        $result = $crawler
            ->filterXpath('//img')
            ->extract(array('src'));

        foreach ($result as $image) {
            $path = $this->folder . "/" . basename($image);
            $file = file_get_contents($image);
            $insert = file_put_contents($path, $file);
            if (!$insert) {
                echo "fail to insert file to folder";
                exit;
            }
        }
    }

    public function createZip()
    {
        $folderFiles = scandir($this->folder);
        if (!$folderFiles) {
            echo "fail to scan folder";
            exit;
        }
        $fileArray = array();
        foreach ($folderFiles as $file) {
            if (($file != ".")
                && ($file != "..")
            ) {
                $fileArray[] = $this->folder . "/" . $file;
            }
        }

        if (create_zip($fileArray, $this->fileName . '.zip')) {
            $this->status = <<<HTML
    File successfully archived. 
    <a href="$this->fileName.zip">Download it now</a>
HTML;
        } else {
            $this->status = "An error occurred";
        }
    }

    public function deleteCreatedFolder()
    {
        $dp = opendir($this->folder)
        or die ('ERROR: Cannot open directory');
        while ($file = readdir($dp)) {
            if ($file != '.' && $file != '..') {
                if (is_file("$this->folder/$file")) {
                    unlink("$this->folder/$file");
                }
            }
        }
        rmdir($this->folder) or die ('could not delete folder');
    }

    public function getStatus()
    {
        echo $this->status;
    }

    public function process()
    {
        $this->domCrawler();
        $this->createZip();
        $this->deleteCreatedFolder();
        $this->getStatus();
    }
}