<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\IdentityProvider;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function oauthCallback($provider)
    {
        // dd(Socialite::with($provider)->user());
        // 認証情報が返ってこなかった場合はログイン画面にリダイレクト
        try {
            $socialUser = Socialite::with($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['oauth_error' => '予期せぬエラーが発生しました']);
        }
        // emailなし対応
        $identityProvider = IdentityProvider::firstOrNew(
            ['id' => $socialUser->getId(), 'name' => $provider]
        );
        // emailで検索してユーザーが見つかればそのユーザーを、見つからなければ新しいインスタンスを生成
        $user = User::firstOrNew(['email' => $socialUser->getEmail()]);
        // ユーザーが認証済みか確認
        if (!empty($socialUser->getEmail()) && $user->exists) {
            if ($user->identityProvider->name != $provider) {
                return redirect('/login')->withErrors(['oauth_error' => 'このメールアドレスはすでに別の認証で使われてます']);
            }
        } elseif ($identityProvider->exists) {
            if ($identityProvider->name != $provider) {
                return redirect('/login')->withErrors(['oauth_error' => 'このメールアドレスはすでに別の認証で使われてます']);
            }
            $user = $identityProvider->user;
        } else {
            $user->name = $socialUser->getNickname() ?? $socialUser->name;
            $identityProvider = new IdentityProvider([
                'id' => $socialUser->getId(),
                'name' => $provider
            ]);

            DB::beginTransaction();
            try {
                $user->save();
                $user->identity_provider()->save($identityProvider);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()
                    ->route('login')
                    // ->withErrors(['transaction_error' => '保存に失敗しました']);
                    ->withErrors(['transaction_error' => $e->getMessage()]);
            }
        }
        // ログイン
        Auth::login($user);

        // return redirect()->route('root');
        return redirect(RouteServiceProvider::HOME);
    }
}
