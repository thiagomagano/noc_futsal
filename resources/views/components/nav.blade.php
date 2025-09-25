 <header class="w-full mb-6 ">
     <nav class="navbar bg-base-700 shadow-sm">
         <div class="flex-1">
             <a class="btn btn-ghost text-xl">N.O.C</a>
         </div>
         <div class="flex-none">
             <ul class="menu menu-horizontal px-1">
                 <li><a href={{ route('jogadores') }}>Jogadores</a></li>
                 <li>
                     <details>
                         <summary>Partida</summary>
                         <ul class="bg-base-100 rounded-t-none p-2">
                             <li><a>Organizar</a></li>
                             <li><a>Histórico</a></li>
                         </ul>
                     </details>
                 </li>
                 <li>

                 </li>
             </ul>
         </div>
         <form method="POST" action="{{ route('logout') }}">
             @csrf
             <button type="submit" class="btn btn-ghost text-sm">Sair</button>
         </form>
     </nav>
 </header>
