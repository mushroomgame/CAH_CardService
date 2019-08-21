<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CardController extends BaseController
{
    /**
     * 获取数据库中的卡牌
     *
     * @param Request $request
     * @param string $type
     * @return Response
     */
    function getCards(Request $request, $type)
    {
        $tags = $request->input('tags');

        $result = DB::table('cards')->where([
                ['type', '=', $type],
                ['enabled', '=', true]
            ]);

        if ($tags != null) $result = $result->whereRaw('0'); 
        
        foreach (json_decode($tags) as $tag)
        {
            $result = $result->orWhereRaw("JSON_CONTAINS(tags, '?')", [$tag]);
        }

        return response()->json($result);
    }

    /**
     * 添加一张卡牌
     *
     * @param Request $request
     * @param string $type
     * @return Response
     */
    function addCard(Request $request, $type)
    {
        $text = $request->input('text');
        $tags = $request->input('tags');

        $result = DB::table('cards')->insert([
                'type' => $type,
                'text' => $text,
                'tags' => $tags
            
        ]);

        return response()->json($result === true ?
        [
            'status' => 'success'
        ] :
        [
            'status' => 'failed',
            'reason' => 'Unknown'
        ]);
    }

    /**
     * 修改一张卡牌
     *
     * @param Request $request
     * @param string $type
     * @param int $id
     * @return Response
     */
    function modCard(Request $request, $type, $id)
    {
        $text = $request->input('text');
        $tags = $request->input('tags');

        $result = DB::table('cards')->where([
            ['id', '=', $id],
            ['type', '=', $type]
        ])->update(
            ['text' => $text],
            ['tags' => $tags]
        );

        return response()->json($result === true ?
            [
                'status' => 'success'
            ] :
            [
                'status' => 'failed',
                'reason' => 'Unknown'
            ]);
    }

    /**
     * 给卡牌投票
     *
     * @param Request $request
     * @param string $type
     * @param int id
     * @return Response
     */
    function voteCard(Request $request, $type, $id)
    {
        $state = $request->input('state');

        $result = DB::table('cards')->where([
            ['id', '=', $id],
            ['type', '=', $type]
        ])->increment('votes');

        if ($state == "up") $result = $result && DB::table('cards')->where([
            ['id', '=', $id],
            ['type', '=', $type]
        ])->increment('vote_up');
        
        return response()->json($result === true ?
        [
            'status' => 'success'
        ] :
        [
            'status' => 'failed',
            'reason' => 'Unknown'
        ]);
    }
}
