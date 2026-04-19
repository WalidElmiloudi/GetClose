      <form
          class="h-[85%] w-[90%] 2xl:w-[80%] 2xl:h-[70%] flex flex-col items-center rounded-lg bg-red-100/20 p-2 gap-2 2xl:gap-3"
          action="{{ route('register') }}" method="post">
          <label class="text-xl self-start lg:text-2xl" for="name"> Full Name :</label>
          <input class="h-10 rounded-lg w-full bg-white pl-2 text-xl lg:text-2xl lg:py-2" type="text" name="name"
              placeholder="Jean Do">
          <label class="text-xl self-start lg:text-2xl" for="email"> Email :</label>
          <input class="h-10 rounded-lg w-full bg-white pl-2 text-lg lg:text-xl lg:py-2" type="email" name="name"
              placeholder="JeanDo@example.domain">
          <label class="text-xl self-start lg:text-2xl" for="role">Choose Your Role :</label>
          <select class="h-10 rounded-lg w-full bg-white pl-2 text-xl lg:text-2xl lg:py-2 2xl:text-xl" name="role">
              <option value="Client">Client</option>
              <option value="Vendor">Vendor</option>
          </select>
          <label class="text-xl self-start lg:text-2xl" for="password"> Password :</label>
          <input class="h-10 rounded-lg w-full bg-white pl-2 text-xl lg:text-2xl lg:py-2" type="password"
              name="password" placeholder="********">
          <label class="text-xl self-start lg:text-2xl" for="password_confirmation"> Password Confirmation:</label>
          <input class="h-10 rounded-lg w-full bg-white pl-2 text-xl lg:text-2xl lg:py-2" type="password"
              name="password_confirmation" placeholder="********">
          <button
              class="h-10 rounded-lg w-full bg-red-500 text-white font-bold text-xl lg:text-2xl cursor-pointer hover:bg-red-900"
              type="submit">REGISTER</button>
      </form>
