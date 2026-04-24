      <form class="h-[55%] w-[90%] 2xl:w-[80%] 2xl:h-[70%] flex flex-col items-center rounded-lg bg-red-100/20 p-4 gap-3"
          action="{{ route('login') }}" method="post">
          <label class="text-xl self-start lg:text-2xl" for="email"> Email :</label>
          <input class="h-10 rounded-lg w-full bg-white pl-2 text-lg lg:text-xl py-2" type="email" name="email"
              placeholder="JeanDo@example.domain">
          <label class="text-xl self-start lg:text-2xl" for="password"> Password :</label>
          <input class="h-10 rounded-lg w-full bg-white pl-2 text-xl lg:text-2xl py-2" type="password" name="password"
              placeholder="********">
          <div class="w-full flex justify-end">
              <a href="{{ route('password.request') }}" class="text-sm text-red-600 hover:text-red-900 font-semibold">Forgot password?</a>
          </div>
          <button
              class="h-10 rounded-lg w-full bg-red-500 text-white font-bold text-xl lg:text-2xl cursor-pointer hover:bg-red-900"
              type="submit">LOGIN</button>
      </form>
