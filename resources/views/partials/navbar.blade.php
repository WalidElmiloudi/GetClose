  <nav class="sticky top-20 z-100">
      @if(auth()->check() && auth()->user()->role === 'vendor')
          <!-- Vendor Navbar -->
          <div
              class="lg:hidden fixed bottom-0 h-20 w-full shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] grid grid-cols-7 bg-white">
              <a href="/" class="flex justify-center items-center {{ request()->routeIs('home') ? 'bg-red-500' : '' }} rounded-full m-2"><i
                      class="ph-fill ph-house text-4xl {{ request()->routeIs('home') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('shops') }}" class="flex justify-center items-center {{ request()->routeIs('shops') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-storefront text-4xl {{ request()->routeIs('shops') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('products') }}" class="flex justify-center items-center {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-tag text-4xl {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('vendor.dashboard') }}" class="flex justify-center items-center {{ request()->routeIs('vendor.dashboard') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-gear text-4xl {{ request()->routeIs('vendor.dashboard') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('vendor.financials') }}" class="flex justify-center items-center {{ request()->routeIs('vendor.financials*') || request()->routeIs('vendor.payouts*') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-currency-dollar text-4xl {{ request()->routeIs('vendor.financials*') || request()->routeIs('vendor.payouts*') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('notifications') }}" class="flex justify-center items-center {{ request()->routeIs('notifications') ? 'bg-red-500' : '' }} rounded-full m-2 relative">
                  <i class="ph ph-bell text-4xl {{ request()->routeIs('notifications') ? 'text-white' : '' }}"></i>
                  @if($notificationCount > 0)
                      <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                          {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                      </span>
                  @endif
              </a>
              <a href="{{ route('profile.view') }}" class="flex justify-center items-center {{ request()->routeIs('profile.*') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-user text-4xl {{ request()->routeIs('profile.*') ? 'text-white' : '' }}"></i></a>
          </div>
          <div
              class="hidden lg:grid 2xl:hidden fixed left-0 bottom-0 z-0  w-20 h-[calc(100vh-80px)]  shadow-[5px_0_5px_-3px_rgba(0,0,0,0.1)] grid-rows-7 bg-white">
              <a href="/" class="flex justify-center items-center {{ request()->routeIs('home') ? 'bg-red-500' : '' }} rounded-full m-2"><i
                      class="ph-fill ph-house text-4xl {{ request()->routeIs('home') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('shops') }}" class="flex justify-center items-center {{ request()->routeIs('shops') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-storefront text-4xl {{ request()->routeIs('shops') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('products') }}" class="flex justify-center items-center {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-tag text-4xl {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('vendor.dashboard') }}" class="flex justify-center items-center {{ request()->routeIs('vendor.dashboard') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-gear text-4xl {{ request()->routeIs('vendor.dashboard') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('vendor.financials') }}" class="flex justify-center items-center {{ request()->routeIs('vendor.financials*') || request()->routeIs('vendor.payouts*') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-currency-dollar text-4xl {{ request()->routeIs('vendor.financials*') || request()->routeIs('vendor.payouts*') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('notifications') }}" class="flex justify-center items-center {{ request()->routeIs('notifications') ? 'bg-red-500' : '' }} rounded-full m-2 relative">
                  <i class="ph ph-bell text-4xl {{ request()->routeIs('notifications') ? 'text-white' : '' }}"></i>
                  @if($notificationCount > 0)
                      <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                          {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                      </span>
                  @endif
              </a>
              <a href="{{ route('profile.view') }}" class="flex justify-center items-center {{ request()->routeIs('profile.*') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-user text-4xl {{ request()->routeIs('profile.*') ? 'text-white' : '' }}"></i></a>
          </div>
          <div class="hidden 2xl:grid top-20 h-20 shadow-sm  w-full grid-cols-7 bg-white">
              <a href="/"
                  class="flex justify-center items-center {{ request()->routeIs('home') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Home</a>
              <a href="{{ route('shops') }}"
                  class="flex justify-center items-center {{ request()->routeIs('shops') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Shops</a>
              <a href="{{ route('products') }}"
                  class="flex justify-center items-center {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Products</a>
              <a href="{{ route('vendor.dashboard') }}"
                  class="flex justify-center items-center {{ request()->routeIs('vendor.dashboard') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Dashboard</a>
              <a href="{{ route('vendor.financials') }}"
                  class="flex justify-center items-center {{ request()->routeIs('vendor.financials*') || request()->routeIs('vendor.payouts*') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Financials</a>
              <a href="{{ route('notifications') }}"
                  class="flex justify-center items-center {{ request()->routeIs('notifications') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150 relative">
                  Notifications
                  @if($notificationCount > 0)
                      <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                          {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                      </span>
                  @endif
              </a>
              <a href="{{ route('profile.view') }}"
                  class="flex justify-center items-center {{ request()->routeIs('profile.*') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Profile</a>
          </div>
      @elseif(auth()->check() && auth()->user()->role === 'admin')
          <!-- Admin Navbar -->
          <div
              class="lg:hidden fixed bottom-0 h-20 w-full shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] grid grid-cols-6 bg-white">
              <a href="/" class="flex justify-center items-center {{ request()->routeIs('home') ? 'bg-red-500' : '' }} rounded-full m-2"><i
                      class="ph-fill ph-house text-4xl {{ request()->routeIs('home') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('shops') }}" class="flex justify-center items-center {{ request()->routeIs('shops') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-storefront text-4xl {{ request()->routeIs('shops') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('products') }}" class="flex justify-center items-center {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-tag text-4xl {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('admin.dashboard') }}" class="flex justify-center items-center {{ request()->routeIs('admin.*') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-shield-check text-4xl {{ request()->routeIs('admin.*') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('notifications') }}" class="flex justify-center items-center {{ request()->routeIs('notifications') ? 'bg-red-500' : '' }} rounded-full m-2 relative">
                  <i class="ph ph-bell text-4xl {{ request()->routeIs('notifications') ? 'text-white' : '' }}"></i>
                  @if($notificationCount > 0)
                      <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                          {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                      </span>
                  @endif
              </a>
              <a href="{{ route('profile.view') }}" class="flex justify-center items-center {{ request()->routeIs('profile.*') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-user text-4xl {{ request()->routeIs('profile.*') ? 'text-white' : '' }}"></i></a>
          </div>
          <div
              class="hidden lg:grid 2xl:hidden fixed left-0 bottom-0 z-0  w-20 h-[calc(100vh-80px)]  shadow-[5px_0_5px_-3px_rgba(0,0,0,0.1)] grid-rows-6 bg-white">
              <a href="/" class="flex justify-center items-center {{ request()->routeIs('home') ? 'bg-red-500' : '' }} rounded-full m-2"><i
                      class="ph-fill ph-house text-4xl {{ request()->routeIs('home') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('shops') }}" class="flex justify-center items-center {{ request()->routeIs('shops') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-storefront text-4xl {{ request()->routeIs('shops') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('products') }}" class="flex justify-center items-center {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-tag text-4xl {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('admin.dashboard') }}" class="flex justify-center items-center {{ request()->routeIs('admin.*') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-shield-check text-4xl {{ request()->routeIs('admin.*') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('notifications') }}" class="flex justify-center items-center {{ request()->routeIs('notifications') ? 'bg-red-500' : '' }} rounded-full m-2 relative">
                  <i class="ph ph-bell text-4xl {{ request()->routeIs('notifications') ? 'text-white' : '' }}"></i>
                  @if($notificationCount > 0)
                      <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                          {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                      </span>
                  @endif
              </a>
              <a href="{{ route('profile.view') }}" class="flex justify-center items-center {{ request()->routeIs('profile.*') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-user text-4xl {{ request()->routeIs('profile.*') ? 'text-white' : '' }}"></i></a>
          </div>
          <div class="hidden 2xl:grid top-20 h-20 shadow-sm  w-full grid-cols-6 bg-white">
              <a href="/"
                  class="flex justify-center items-center {{ request()->routeIs('home') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Home</a>
              <a href="{{ route('shops') }}"
                  class="flex justify-center items-center {{ request()->routeIs('shops') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Shops</a>
              <a href="{{ route('products') }}"
                  class="flex justify-center items-center {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Products</a>
              <a href="{{ route('admin.dashboard') }}"
                  class="flex justify-center items-center {{ request()->routeIs('admin.*') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Admin</a>
              <a href="{{ route('notifications') }}"
                  class="flex justify-center items-center {{ request()->routeIs('notifications') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150 relative">
                  Notifications
                  @if($notificationCount > 0)
                      <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                          {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                      </span>
                  @endif
              </a>
              <a href="{{ route('profile.view') }}"
                  class="flex justify-center items-center {{ request()->routeIs('profile.*') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Profile</a>
          </div>
      @else
          <!-- Client Navbar (Default) -->
          <div
              class="lg:hidden fixed bottom-0 h-20 w-full shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] grid grid-cols-7 bg-white">
              <a href="/" class="flex justify-center items-center {{ request()->routeIs('home') ? 'bg-red-500' : '' }} rounded-full m-2"><i
                      class="ph-fill ph-house text-4xl {{ request()->routeIs('home') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('shops') }}" class="flex justify-center items-center {{ request()->routeIs('shops') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-storefront text-4xl {{ request()->routeIs('shops') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('products') }}" class="flex justify-center items-center {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-tag text-4xl {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('cart') }}" class="flex justify-center items-center {{ request()->routeIs('cart') ? 'bg-red-500' : '' }} rounded-full m-2 relative">
                  <i class="ph ph-shopping-cart text-4xl {{ request()->routeIs('cart') ? 'text-white' : '' }}"></i>
                  @if($cartCount > 0)
                      <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                          {{ $cartCount > 99 ? '99+' : $cartCount }}
                      </span>
                  @endif
              </a>
              <a href="{{ route('orders') }}" class="flex justify-center items-center {{ request()->routeIs('orders') || request()->routeIs('orders.*') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-package text-4xl {{ request()->routeIs('orders') || request()->routeIs('orders.*') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('notifications') }}" class="flex justify-center items-center {{ request()->routeIs('notifications') ? 'bg-red-500' : '' }} rounded-full m-2 relative">
                  <i class="ph ph-bell text-4xl {{ request()->routeIs('notifications') ? 'text-white' : '' }}"></i>
                  @if($notificationCount > 0)
                      <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                          {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                      </span>
                  @endif
              </a>
              <a href="{{ route('profile.view') }}" class="flex justify-center items-center {{ request()->routeIs('profile.*') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-user text-4xl {{ request()->routeIs('profile.*') ? 'text-white' : '' }}"></i></a>
          </div>
          <div
              class="hidden lg:grid 2xl:hidden fixed left-0 bottom-0 z-0  w-20 h-[calc(100vh-80px)]  shadow-[5px_0_5px_-3px_rgba(0,0,0,0.1)] grid-rows-7 bg-white">
              <a href="/" class="flex justify-center items-center {{ request()->routeIs('home') ? 'bg-red-500' : '' }} rounded-full m-2"><i
                      class="ph-fill ph-house text-4xl {{ request()->routeIs('home') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('shops') }}" class="flex justify-center items-center {{ request()->routeIs('shops') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-storefront text-4xl {{ request()->routeIs('shops') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('products') }}" class="flex justify-center items-center {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-tag text-4xl {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('cart') }}" class="flex justify-center items-center {{ request()->routeIs('cart') ? 'bg-red-500' : '' }} rounded-full m-2 relative">
                  <i class="ph ph-shopping-cart text-4xl {{ request()->routeIs('cart') ? 'text-white' : '' }}"></i>
                  @if($cartCount > 0)
                      <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                          {{ $cartCount > 99 ? '99+' : $cartCount }}
                      </span>
                  @endif
              </a>
              <a href="{{ route('orders') }}" class="flex justify-center items-center {{ request()->routeIs('orders') || request()->routeIs('orders.*') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-package text-4xl {{ request()->routeIs('orders') || request()->routeIs('orders.*') ? 'text-white' : '' }}"></i></a>
              <a href="{{ route('notifications') }}" class="flex justify-center items-center {{ request()->routeIs('notifications') ? 'bg-red-500' : '' }} rounded-full m-2 relative">
                  <i class="ph ph-bell text-4xl {{ request()->routeIs('notifications') ? 'text-white' : '' }}"></i>
                  @if($notificationCount > 0)
                      <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                          {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                      </span>
                  @endif
              </a>
              <a href="{{ route('profile.view') }}" class="flex justify-center items-center {{ request()->routeIs('profile.*') ? 'bg-red-500' : '' }} rounded-full m-2"><i class="ph ph-user text-4xl {{ request()->routeIs('profile.*') ? 'text-white' : '' }}"></i></a>
          </div>
          <div class="hidden 2xl:grid top-20 h-20 shadow-sm  w-full grid-cols-6 bg-white">
              <a href="/"
                  class="flex justify-center items-center {{ request()->routeIs('home') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Home</a>
              <a href="{{ route('shops') }}"
                  class="flex justify-center items-center {{ request()->routeIs('shops') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Shops</a>
              <a href="{{ route('products') }}"
                  class="flex justify-center items-center {{ request()->routeIs('products') || request()->routeIs('products.show') || request()->routeIs('search') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Products</a>
              <a href="{{ route('cart') }}"
                  class="flex justify-center items-center {{ request()->routeIs('cart') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150 relative">
                  Cart
                  @if($cartCount > 0)
                      <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                          {{ $cartCount > 99 ? '99+' : $cartCount }}
                      </span>
                  @endif
              </a>
              <a href="{{ route('orders') }}"
                  class="flex justify-center items-center {{ request()->routeIs('orders') || request()->routeIs('orders.*') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Orders</a>
              <a href="{{ route('profile.view') }}"
                  class="flex justify-center items-center {{ request()->routeIs('profile.*') ? 'bg-red-500 text-white' : 'hover:bg-red-400 hover:text-white' }} rounded-full m-2 text-2xl font-bold ease-in-out duration-150">Profile</a>
          </div>
      @endif
  </nav>
