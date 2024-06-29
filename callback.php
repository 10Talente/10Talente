<?php
session_start();

class Util
{
    function redirect( $type, $message )
    {
        $_SESSION['responseMessage'] = array(
            'messageType' => $type,
            'message' => $message
        );
        header( "Locateion: index.php" );
        exit();
    }
}


$util = new Util();


if( isset( $_GET['code'] ) )
{
    require_once "config/SECRET_google.php";
    require_once "core/GoogleDriveUploadService.php";

    $googleDriveUploadService = new GoogleDriveUploadService();
    $googleResponse = $googleDriveUploadService->getAccessToken( Google::GOOGLE_WEB_CLIENT_ID, Google::AUTHORIZED_REDIRECT_URI, Google::GOOGLE_WEB_CLIENT_SECRET, $_GET['code'] );
    $accessToken = $googleResponse['access_token'];

    if( !empty( $accessToken ) )
    {

    }
    else $util->redirect( "error", "Something went wrong. Access forbidden." );
}



-----------
    if (! empty($accessToken)) {

        require_once __DIR__ . '/lib/FileModel.php';
        $fileModel = new FileModel();

        $fileId = $_SESSION['fileInsertId'];

        if (! empty($fileId)) {

            $fileResult = $fileModel->getFileRecordById($fileId);
            if (! empty($fileResult)) {
                $fileName = $fileResult[0]['file_base_name'];
                $filePath = 'data/' . $fileName;
                $fileContent = file_get_contents($filePath);
                $fileSize = filesize($filePath);
                $filetype = mime_content_type($filePath);

                try {
                    // Move file to Google Drive via cURL
                    $googleDriveFileId = $googleDriveUploadService->uploadFileToGoogleDrive($accessToken, $fileContent, $filetype, $fileSize);
                    if ($googleDriveFileId) {
                        $fileMeta = array(
                            'name' => basename($fileName)
                        );
                        // Add file metadata via Google Drive API
                        $googleDriveMeta = $googleDriveUploadService->addFileMeta($accessToken, $googleDriveFileId, $fileMeta);
                        if ($googleDriveMeta) {
                            $fileModel->updateFile($googleDriveFileId, $fileId);

                            $_SESSION['fileInsertId'] = '';
                            $driveLink = '<a href="https://drive.google.com/open?id=' . $googleDriveMeta['id'] . '" target="_blank"><b>Open in Google Drive</b></a>.';
                            $util->redirect("success", 'File uploaded. ' . $driveLink);
                        }
                    }
                } catch (Exception $e) {
                    $util->redirect("error", $e->getMessage());
                }
            } else {
                $util->redirect("error", 'Failed to get the file content.');
            }
        } else {
            $util->redirect("error", 'File id not found.');
        }
    } else {
        $util->redirect("error", 'Something went wrong. Access forbidden.');
    }
}
?>