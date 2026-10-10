<?php


class Mailer{

    public static function sendClientNotification($user_id, $body,$subject)
    {
        $ctx = &get_instance();
        if (!isset($ctx->mMailer)) {
            $ctx->load->model("simple_mailer/Mailer_model", "mMailer");
        }
        $ctx->mMailer->sendSimpleNotification($user_id, $body,$subject);

    }

    public static function sendAdminNotification($body,$subject)
    {
        $ctx = &get_instance();
        if (!isset($ctx->mMailer)) {
            $ctx->load->model("simple_mailer/Mailer_model", "mMailer");
        }
        $ctx->mMailer->sendAdminNotification($body,$subject);

    }
}