<h1 class="title">Timesheets</h1>
<div class="space">
    <div class="position">
        <table class="table">
            <thead>
                <th>Calendario</th>
                <th>Tipo</th>
                <th>Entrada</th>
                <th>Salida</th>
            </thead>
            <tbody>
                @foreach ($timesheets as $item)
                <tr>
                    <td>{{ $item->calendar->name }}</td>
                    <td>{{ $item->type }}</td>
                    <td>{{ $item->day_in }}</td>
                    <td>{{ $item->day_out }}</td>
                </tr>

                @endforeach
                
            </tbody>
        </table>
    </div>
</div>
<style>
    table, thead, tbody, tr, td, th {
        border: 1px solid black;
    }
    .space {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }
    .title {
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }
    .position{
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: scroll;
        background-color: white;
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    .table{
        width: 100%;
        text-align: left;
        min-width: 100%;
        table-layout: auto;
    }
</style>

<!-- <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
  <h3 class="text-lg font-semibold ml-3 text-slate-800">Timesheets</h3>
 </div>
 <div class="relative flex flex-col w-full h-full overflow-scroll text-gray-700 bg-white shadow-md rounded-lg bg-clip-border">
  <table class="w-full text-left table-auto min-w-max">
    <thead>
        <tr>
            <th class="p-4 border-b border-slate-300 bg-slate-50">
                <p class="block text-sm font-normal leading-none text-slate-500">
                    Calendario
                </p>
            </th>
            <th class="p-4 border-b border-slate-300 bg-slate-50">
                <p class="block text-sm font-normal leading-none text-slate-500">
                    Tipo
                </p>
            </th>
            <th class="p-4 border-b border-slate-300 bg-slate-50">
                <p class="block text-sm font-normal leading-none text-slate-500">
                    Entrada
                </p>
            </th>
            <th class="p-4 border-b border-slate-300 bg-slate-50">
                <p class="block text-sm font-normal leading-none text-slate-500">
                    Salida
                </p>
            </th>
        </tr>
    </thead>
    <tbody>
    @foreach ($timesheets as $item)
        <tr class="hover:bg-slate-50">
            <td class="p-3 border-b border-slate-200">
                <p class="block text-sm text-slate-800">
                {{ $item->calendar->name }}
                </p>
            </td>
            <td class="p-7 border-b border-slate-200">
                <p class="block text-sm text-slate-800">
                {{ $item->type }}
                </p>
            </td>
            <td class="p-7 border-b border-slate-200">
                <p class="block text-sm text-slate-800">
                {{ $item->day_in }}
                </p>
            </td>
            <td class="p-10 border-b border-slate-200">
                <p class="block text-sm text-slate-800">
                {{ $item->day_out }}
                </p>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
</div>
  -->