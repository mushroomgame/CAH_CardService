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
     * @return Response
     */
    function getCards(Request $request)
    {
        $type = $request->type;
        $tags = $request->input('tags');

        $result = DB::table($type)->where('status', 1);

        if ($tags != null) $result->where(function ($query) use ($tags)
        {
            $query->whereRaw('0');

            foreach (json_decode($tags) as $tag)
            {
                $query->orWhereRaw('JSON_CONTAINS(`tags`, ?)', json_encode($tag));
            }
        });

        return response()->json($result->get());
    }

    /**
     * 添加一张卡牌
     *
     * @param Request $request
     * @return Response
     */
    function addCard(Request $request)
    {
        $type = $request->type;
        $text = $request->input('text');
        $tags = $request->input('tags');

        $result = DB::table($type)->insert([
            'text' => $text,
            'tags' => $tags,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json($result ? [
            'status' => 'success'
        ] : [
            'status' => 'failed',
            'reason' => 'Nothing happened.'
        ]);
    }

    /**
     * 修改一张卡牌
     *
     * @param Request $request
     * @return Response
     */
    function modCard(Request $request)
    {
        $type = $request->type;
        $id = $request->id;

        //Combine params
        $sum = [
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $text = $request->input('text');
        $tags = $request->input('tags');
        $status = $request->input('status');
        if ($text) $sum['text'] = $text;
        if ($tags) $sum['tags'] = $tags;
        if ($status) $sum['status'] = $status;

        $result = DB::table($type)->where('_id', $id)->update($sum);

        return response()->json($result ? [
            'status' => 'success'
        ] : [
            'status' => 'failed',
            'reason' => 'Nothing happened.'
        ]);
    }

    /**
     * 删除一张卡牌
     *
     * @param Request $request
     * @return Response
     */
    function deleteCard(Request $request, $type, $id)
    {
        $type = $request->type;
        $id = $request->id;

        $result = DB::table($type)->where('_id', $id)->delete();

        return response()->json($result ? [
            'status' => 'success'
        ] : [
            'status' => 'failed',
            'reason' => 'Nothing happened.'
        ]);
    }

    /**
     * 给卡牌投票
     *
     * @param Request $request
     * @return Response
     */
    function voteCard(Request $request)
    {
        $type = $request->type;
        $id = $request->id;
        $vote = $request->input('vote');

        $result = DB::table($type)->where('_id', $id)->increment('plays');

        if ($vote == 'up') $result = $result &&
            DB::table($type)->where('_id', $id)->increment('votes');

        return response()->json($result ? [
            'status' => 'success'
        ] : [
            'status' => 'failed',
            'reason' => 'Nothing happened.'
        ]);
    }
}
