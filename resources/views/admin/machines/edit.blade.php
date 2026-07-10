<x-app-layout>


<x-slot name="header">

<h2 class="font-semibold text-2xl text-gray-800">
    Edit Machine
</h2>

</x-slot>



<div class="py-8">

<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">


<form method="POST"
      action="{{ route('admin.machines.update',$machine) }}">

@csrf

@method('PUT')



<div class="mb-4">

<label>
Machine Name
</label>

<input type="text"
       name="machine_name"
       value="{{ $machine->machine_name }}"
       class="w-full border rounded-lg p-2">

</div>




<div class="mb-4">

<label>
Machine Code
</label>


<input type="text"
       name="machine_code"
       value="{{ $machine->machine_code }}"
       class="w-full border rounded-lg p-2">


</div>




<div class="mb-4">

<label>
Status
</label>


<select name="status"
        class="w-full border rounded-lg p-2">


<option {{ $machine->status=='Available'?'selected':'' }}>
Available
</option>


<option {{ $machine->status=='In Use'?'selected':'' }}>
In Use
</option>


<option {{ $machine->status=='Maintenance'?'selected':'' }}>
Maintenance
</option>


</select>


</div>




<button class="bg-blue-600 text-white px-5 py-2 rounded-lg">

Update Machine

</button>



<a href="{{ route('admin.machines.index') }}"
   class="ml-3 text-gray-600">

Cancel

</a>



</form>


</div>

</div>


</x-app-layout>