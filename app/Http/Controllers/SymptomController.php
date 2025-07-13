<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI; // OpenAI PHP SDK가 있다고 가정
use App\Models\SymptomKeyword;
use App\Models\UserKeyword;

class SymptomController extends Controller
{
    public function storeKeyword(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => '로그인이 필요합니다.'], 401);
        }

        $keyword = $request->input('keyword');
        if (!$keyword) {
            return response()->json(['error' => '키워드 없음'], 400);
        }

        try {
            \Log::channel('single')->info('🟩 storeKeyword 함수 진입');
            \Log::channel('single')->info('keyword: ' . $request->input('keyword'));
            
            $symptom = SymptomKeyword::firstOrCreate(['keyword' => $keyword]);

            UserKeyword::create([
                'user_id' => auth()->user()->id,
                'keyword_id' => $symptom->id,
            ]);
            \Log::channel('single')->info('✅ 저장 완료 응답 직전');
            return response()->json(['message' => '저장 완료']);
        } catch (\Exception $e) {
        \Log::channel('single')->error('❌ storeKeyword error: ' . $e->getMessage());
            return response()->json(['error' => '서버 오류: ' . $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        $symptom = $request->input('symptom');
        if (!$symptom) {
            return response()->json(['error' => '증상을 입력해 주세요.'], 400);
        }

        try {
            $openai = OpenAI::client(env('OPENAI_API_KEY'));

            $completion = $openai->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => <<<EOT
당신은 의료 지식에 기반한 증상 분석 전문가입니다.
사용자가 입력한 문장에서 오직 의학적으로 의미 있는 신체 증상과 관련된 핵심 키워드(단, 확실하게 구분될 수 있도록)를 최대 10개 이상 최대한 많이 추출하십시오.
키워드는 반드시 콤마(,)로 구분된 한 줄로 간결하게 출력하십시오.
한 개만 출력하지 말고, 가능한 많이 출력해야 합니다.
관련 없는 키워드, 번호, 설명, 진단, 추가 문장은 절대 포함하지 마십시오.
입력 내용이 의학적 증상 또는 건강과 관련 없으면, 키워드 대신 "이것을 찾으셨나요? {가장 유사한 의학적 증상 키워드}" 형식으로만 답하십시오.
EOT
                    ],
                    [
                        'role' => 'user',
                        'content' => $symptom . "\n아래 규칙에 맞춰 최대한 많은 의학적 증상 키워드를 출력해 주세요."
                    ]
                ],
            ]);

            $reply = trim($completion->choices[0]->message->content);

            if (str_starts_with($reply, '이것을 찾으셨나요?')) {
                return response()->json(['keywords' => [$reply]]);
            }

            $keywords = array_slice(array_filter(array_map('trim', explode(',', $reply))), 0, 10);

            return response()->json(['keywords' => $keywords]);

        } catch (\Exception $e) {
            \Log::error('OpenAI Error: '.$e->getMessage());
            return response()->json(['error' => $e->getMessage() ?: '서버 오류'], 500);
        }
    }
    public function getRecentKeywords()
    {
        if (!auth()->check()) {
            return response()->json(['error' => '로그인이 필요합니다.'], 401);
        }

        $keywords = UserKeyword::with('symptomKeyword')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($entry) {
                return [
                    'date' => $entry->created_at->timezone('Asia/Seoul')->format('Y.m.d'),
                    'keyword' => $entry->symptomKeyword->keyword ?? '',
                ];
            })->values(); // 인덱스 재정렬

        return response()->json(['keywords' => $keywords]);
    }

    public function recentKeywords()
    {
        if (!auth()->check()) {
            return response()->json([]);
        }

        $grouped = UserKeyword::with('keyword')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(50)
            ->get()
            ->groupBy(function ($item) {
                return optional($item->created_at)->timezone('Asia/Seoul')->format('Y.m.d');
            });

        $result = [];

        foreach ($grouped as $date => $items) {
            $result[$date] = $items->map(fn($item) => [
                'keyword' => optional($item->keyword)->keyword ?? '',
                'date' => $item->created_at->timezone('Asia/Seoul')->format('Y.m.d'),
            ]);
        }

        return response()->json($result);
    }
}
