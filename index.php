<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   Vendor Invoice
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <script>
   document.addEventListener('DOMContentLoaded', function () {
     const laporanButton = document.getElementById('laporan-button');
     const laporanDropdown = document.getElementById('laporan-dropdown');

     laporanButton.addEventListener('click', function () {
       laporanDropdown.classList.toggle('hidden');
     });

     const sidebarItems = document.querySelectorAll('.sidebar-item');
     sidebarItems.forEach(item => {
       item.addEventListener('click', function () {
         const target = this.getAttribute('data-target');
         loadContent(target);
       });
     });

     function loadContent(target) {
       fetch(target)
         .then(response => response.text())
         .then(data => {
           document.getElementById('main-content').innerHTML = data;
         });
     }
   });
  </script>
  <style>
   .sidebar-item:hover {
     background-color: #2f855a;
     cursor: pointer;
     transition: background-color 0.3s ease;
   }
  </style>
 </head>
 <body class="bg-gray-100">
  <div class="flex">
   <!-- Sidebar -->
   <div class="w-64 bg-green-700 text-white min-h-screen">
    <div class="p-4">
     <div class="text-2xl font-bold mb-4">
      Hotel Reservasi
     </div>
     <div class="mb-4">
      <input class="w-full p-2 rounded bg-green-600 placeholder-white" placeholder="Search" type="text"/>
     </div>
     <div class="mb-4 sidebar-item" data-target="reservasi.php">
      <div class="font-bold">
       <i class="fas fa-hotel mr-2"></i> Hotel XYZ
      </div>
      <div class="text-sm">
       <i class="fas fa-home mr-2"></i> Home
      </div>
     </div>
     <div class="mb-4 sidebar-item" data-target="pendaftaran.php">
      <div class="font-bold">
       <i class="fas fa-user-plus mr-2"></i> Pendaftaran
      </div>
     </div>
     <div class="mb-4 sidebar-item">
      <div class="font-bold cursor-pointer" id="laporan-button">
       <i class="fas fa-file-alt mr-2"></i> Laporan
      </div>
      <ul class="text-sm hidden" id="laporan-dropdown">
       <li class="py-1 sidebar-item" data-target="log_reservasi.php">
        <i class="fas fa-book mr-2"></i> Log Reservasi
       </li>
       <li class="py-1 sidebar-item" data-target="daftar_tamu.php">
        <i class="fas fa-users mr-2"></i> Daftar Tamu
       </li>
      </ul>
     </div>
     <div class="mb-4 sidebar-item" data-target="reservasi.php">
      <div class="font-bold">
       <i class="fas fa-calendar-check mr-2"></i> Reservasi
      </div>
     </div>
     <div class="mb-4 sidebar-item" data-target="pembayaran.php">
      <div class="font-bold">
       <i class="fas fa-credit-card mr-2"></i> Pembayaran
      </div>
     </div>
     <div class="mb-4 sidebar-item" data-target="kamar.php">
      <div class="font-bold">
       <i class="fas fa-bed mr-2"></i> Kamar
      </div>
     </div>
    </div>
   </div>
   <!-- Main Content -->
   <div class="flex-1 p-6" id="main-content">
    <div class="flex justify-between items-center mb-6">
     <div class="text-2xl font-bold">
      Vendor Invoice
     </div>
     <div class="flex items-center">
      <div class="mr-4">
       <i class="fas fa-bell text-xl">
       </i>
      </div>
      <div class="flex items-center">
       <img alt="User profile picture" class="rounded-full mr-2" height="40" src="https://storage.googleapis.com/a1aa/image/VBYCIaB86wRJbQVYkT59MAWFiSAS3HFRaBhtacMaYc8.jpg" width="40"/>
       <div>
        Filip Justic
       </div>
      </div>
     </div>
    </div>
    <div class="bg-white p-6 rounded shadow">
     <div class="mb-6">
      <div class="flex justify-between items-center">
       <div>
        <div class="font-bold">
         US Foods
        </div>
        <div class="text-sm">
         Receive Date: 03/22/2018
        </div>
        <div class="text-sm">
         Invoice Date: 03/22/2018
        </div>
       </div>
       <div>
        <div class="text-sm">
         Invoice No: 185432asd
        </div>
        <div class="text-sm">
         Order No: 354
        </div>
        <div class="text-sm">
         Due Date: 03/22/2018
        </div>
        <div class="text-sm">
         Amount: $575.00
        </div>
       </div>
       <div>
        <button class="bg-green-500 text-white px-4 py-2 rounded">
         Edit
        </button>
       </div>
      </div>
     </div>
     <div class="overflow-x-auto">
      <table class="min-w-full bg-white">
       <thead>
        <tr>
         <th class="py-2 px-4 border-b">
          #
         </th>
         <th class="py-2 px-4 border-b">
          SKU
         </th>
         <th class="py-2 px-4 border-b">
          ITEMS
         </th>
         <th class="py-2 px-4 border-b">
          QUANTITY
         </th>
         <th class="py-2 px-4 border-b">
          PRICE
         </th>
         <th class="py-2 px-4 border-b">
          AMOUNT
         </th>
         <th class="py-2 px-4 border-b">
         </th>
        </tr>
       </thead>
       <tbody>
        <tr>
         <td class="py-2 px-4 border-b">
          01
         </td>
         <td class="py-2 px-4 border-b">
          Artichoke H
         </td>
         <td class="py-2 px-4 border-b">
          Artichoke H eart 6x4.5oz (Dry/Canned, 1000 Dry)
         </td>
         <td class="py-2 px-4 border-b">
          3case
         </td>
         <td class="py-2 px-4 border-b">
          $50.00
         </td>
         <td class="py-2 px-4 border-b">
          $250.50
         </td>
         <td class="py-2 px-4 border-b text-center">
          <button class="text-red-500">
           <i class="fas fa-trash">
           </i>
          </button>
         </td>
        </tr>
        <tr>
         <td class="py-2 px-4 border-b">
          02
         </td>
         <td class="py-2 px-4 border-b">
          Bacon, Sliced
         </td>
         <td class="py-2 px-4 border-b">
          Bacon, Sliced 15x1lb (Meat, 100 Meat)
         </td>
         <td class="py-2 px-4 border-b">
          2case
         </td>
         <td class="py-2 px-4 border-b">
          $2.00
         </td>
         <td class="py-2 px-4 border-b">
          $100.00
         </td>
         <td class="py-2 px-4 border-b text-center">
          <button class="text-red-500">
           <i class="fas fa-trash">
           </i>
          </button>
         </td>
        </tr>
        <tr>
         <td class="py-2 px-4 border-b">
          03
         </td>
         <td class="py-2 px-4 border-b">
          Bison, Ground
         </td>
         <td class="py-2 px-4 border-b">
          Bison, Ground 15x1lb (Meat, 100 Meat)
         </td>
         <td class="py-2 px-4 border-b">
          2case
         </td>
         <td class="py-2 px-4 border-b">
          $2.00
         </td>
         <td class="py-2 px-4 border-b">
          $100.00
         </td>
         <td class="py-2 px-4 border-b text-center">
          <button class="text-red-500">
           <i class="fas fa-trash">
           </i>
          </button>
         </td>
        </tr>
        <tr>
         <td class="py-2 px-4 border-b">
          04
         </td>
         <td class="py-2 px-4 border-b">
          Bison, Sliced
         </td>
         <td class="py-2 px-4 border-b">
          Bison, Sliced 15x1lb (Meat, 100 Meat)
         </td>
         <td class="py-2 px-4 border-b">
          2case
         </td>
         <td class="py-2 px-4 border-b">
          $2.00
         </td>
         <td class="py-2 px-4 border-b">
          $100.00
         </td>
         <td class="py-2 px-4 border-b text-center">
          <button class="text-red-500">
           <i class="fas fa-trash">
           </i>
          </button>
         </td>
        </tr>
        <tr>
         <td class="py-2 px-4 border-b">
          05
         </td>
         <td class="py-2 px-4 border-b">
          Bison, Sliced
         </td>
         <td class="py-2 px-4 border-b">
          Bison, Sliced 15x1lb (Meat, 100 Meat)
         </td>
         <td class="py-2 px-4 border-b">
          2case
         </td>
         <td class="py-2 px-4 border-b">
          $2.00
         </td>
         <td class="py-2 px-4 border-b">
          $100.00
         </td>
         <td class="py-2 px-4 border-b text-center">
          <button class="text-red-500">
           <i class="fas fa-trash">
           </i>
          </button>
         </td>
        </tr>
        <tr>
         <td class="py-2 px-4 border-b">
          06
         </td>
         <td class="py-2 px-4 border-b">
          Bison, Ground
         </td>
         <td class="py-2 px-4 border-b">
          Bison, Ground 1x1lb (Meat, 100 Meat)
         </td>
         <td class="py-2 px-4 border-b">
          2lb
         </td>
         <td class="py-2 px-4 border-b">
          $4.04
         </td>
         <td class="py-2 px-4 border-b">
          $8.08
         </td>
         <td class="py-2 px-4 border-b text-center">
          <button class="text-red-500">
           <i class="fas fa-trash">
           </i>
          </button>
         </td>
        </tr>
       </tbody>
      </table>
     </div>
     <div class="flex justify-between items-center mt-4">
      <div>
       <button class="bg-green-500 text-white px-4 py-2 rounded">
        Add Non Inventory Item
       </button>
      </div>
      <div class="text-right">
       <div class="text-sm">
        Sub Total: $1029.10
       </div>
       <div class="text-xl font-bold">
        $1029.10
       </div>
      </div>
     </div>
     <div class="flex justify-between items-center mt-4">
      <div>
       <button class="bg-red-500 text-white px-4 py-2 rounded">
        Delete Invoice
       </button>
      </div>
      <div>
       <button class="bg-green-500 text-white px-4 py-2 rounded">
        Approve Invoice
       </button>
       <button class="bg-green-500 text-white px-4 py-2 rounded ml-2">
        Approve &amp; Next
       </button>
      </div>
     </div>
    </div>
   </div>
  </div>
 </body>
</html>