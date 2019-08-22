<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CardController extends BaseController
{
    /**
     * 获取当前版本信息
     *
     * @param Request $request
     * @return Response
     */
    function getVersion(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'version' => 1
        ]);
    }

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

        if ($tags != null) $result = $result->where(function ($query) use ($tags)
        {
            $query = $query->whereRaw('0');

            foreach (json_decode($tags) as $tag)
            {
                $query = $query->orWhereRaw('JSON_CONTAINS(`tags`, ?)', json_encode($tag));
            }
        });

        DB::connection()->enableQueryLog();

        return response()->json($result->get());
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

        return response()->json($result ? [
            'status' => 'success'
        ] : [
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
        //Authorize
        $secret = $request->input('secret');
        if ($secret != env('APP_SECRET'))
        {
            return response()->json([
                'status' => 'failed',
                'reason' => 'Not authorized.'
            ]);
        }

        //Combine params
        $sum = [];
        $text = $request->input('text');
        $tags = $request->input('tags');
        if ($text) $sum['text'] = $text;
        if ($tags) $sum['tags'] = $tags;

        $result = DB::table('cards')->where([
            ['id', '=', $id],
            ['type', '=', $type]
        ])->update($sum);

        return response()->json($result ? [
            'status' => 'success'
        ] : [
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
        $up = $request->input('up');

        $result = DB::table('cards')->where([
            ['id', '=', $id],
            ['type', '=', $type]
        ])->increment('votes');

        if ($up === 'true') $result = $result && DB::table('cards')->where([
            ['id', '=', $id],
            ['type', '=', $type]
        ])->increment('vote_up');

        return response()->json($result ? [
            'status' => 'success'
        ] : [
            'status' => 'failed',
            'reason' => 'Unknown'
        ]);
    }
}
