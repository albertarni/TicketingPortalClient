<?php
/**
 * Created by PhpStorm.
 * User: ffogarasi
 * Date: 12/27/17
 * Time: 5:52 PM
 */

namespace Albertarni\TicketingPortalClient\Api\News;


use Albertarni\TicketingPortalClient\Api\Model;

class News extends Model
{


    protected $url = 'news';

    protected $fillable = ['id', 'title', 'description', 'start_date', 'end_date','attachment_detail'];

}