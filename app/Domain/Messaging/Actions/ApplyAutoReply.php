<?php

namespace App\Domain\Messaging\Actions;

use App\Domain\Messaging\Models\AutoReplyRule;
use App\Domain\Messaging\Models\Message;

class ApplyAutoReply
{
    public function handle(Message $inbound): ?array
    {
        $rules = AutoReplyRule::query()
            ->where(function ($q) use ($inbound) {
                $q->whereNull('channel')->orWhere('channel', $inbound->channel);
            })
            ->where('enabled', true)
            ->orderBy('priority')
            ->get();

        foreach ($rules as $rule) {
            $pattern = $rule->pattern;

            $match = match ($rule->matcher_type) {
                'equals' => trim(mb_strtolower($inbound->body ?? '')) === trim(mb_strtolower($pattern)),
                'regex' => @preg_match($pattern, $inbound->body ?? '') === 1,
                default => mb_stripos($inbound->body ?? '', $pattern) !== false,
            };

            if ($match) {
                $reply = $this->renderTemplate($rule->reply_template, $inbound);
                return ['body' => $reply, 'rule_id' => $rule->id];
            }
        }

        return null;
    }

    protected function renderTemplate(string $tpl, Message $inbound): string
    {
        $vars = [
            '{{name}}' => $inbound->contact->name ?? 'there',
            '{{channel}}' => ucfirst($inbound->channel),
        ];

        return strtr($tpl, $vars);
    }
}
