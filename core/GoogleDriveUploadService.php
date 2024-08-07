<?php
// https://phppot.com/php/how-to-upload-files-to-google-drive-with-api-using-php/
require_once "..config/SECRET_google.php";

class GoogleDriveUploadService
{

    public function getAccessToken($clientId, $authorizedRedirectURI, $clientSecret, $code)
    {
        $curlPost = 'client_id=' . $clientId . '&redirect_uri=' . $authorizedRedirectURI . '&client_secret=' . $clientSecret . '&code=' . $code . '&grant_type=authorization_code';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, Google::GOOGLE_OAUTH2_TOKEN_URI);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $curlResponse = json_decode(curl_exec($curl), true);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($responseCode != 200) {
            $errorMessage = 'Problem in getting access token';
            if (curl_errno($curl)) {
                $errorMessage = curl_error($curl);
            }
            throw new Exception('Error: ' . $responseCode . ': ' . $errorMessage);
        }

        return $curlResponse;
    }

    public function uploadFileToGoogleDrive($accessToken, $fileContent, $filetype, $fileSize)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, Google::GOOGLE_DRIVE_FILE_UPLOAD_URI . '?uploadType=media');
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fileContent);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: ' . $filetype,
            'Content-Length: ' . $fileSize,
            'Authorization: Bearer ' . $accessToken
        ));

        $curlResponse = json_decode(curl_exec($curl), true);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($responseCode != 200) {
            $errorMessage = 'Failed to upload file to drive';
            if (curl_errno($curl)) {
                $errorMessage = curl_error($curl);
            }
            throw new Exception('Error ' . $responseCode . ': ' . $errorMessage);
        }
        curl_close($curl);
        return $curlResponse['id'];
    }

    public function addFileMeta($accessToken, $googleDriveFileId, $fileMeta)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, Google::GOOGLE_DRIVE_FILE_META_URI . $googleDriveFileId);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fileMeta));
        $curlResponse = json_decode(curl_exec($curl), true);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($responseCode != 200) {
            $errorMessage = 'Failed to add file metadata';
            if (curl_errno($curl)) {
                $errorMessage = curl_error($curl);
            }
            throw new Exception('Error ' . $responseCode . ': ' . $errorMessage);
        }
        curl_close($curl);

        return $curlResponse;
    }
}
?>