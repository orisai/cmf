<script>
	export let isActive = false;
	//export let isFirst = false;
	//export let isHover = false;
	export let getOptionLabel = undefined;
	export let item = undefined;
	export let filterText = '';
	let itemClasses = '';
	$: {
		const _itemClasses = [];
		if (isActive) {
			_itemClasses.push('text-white', 'bg-indigo-800');
		}else{
			_itemClasses.push('text-gray-900');
		}
		itemClasses = _itemClasses.join(' ');
	}
</script>

<div class="cursor-default select-none relative py-2 pl-3 pr-9 hover:text-white hover:bg-indigo-600 {itemClasses}">
	<!-- Selected: "font-semibold", Not Selected: "font-normal" -->
	<span class="font-normal block truncate">
         {@html getOptionLabel(item, filterText)}
	</span>

	<!--
	  Checkmark, only display for selected option.

	  Highlighted: "text-white", Not Highlighted: "text-indigo-600"
	-->
	{#if false}
		<span class="text-indigo-600 absolute inset-y-0 right-0 flex items-center pr-4">
        	<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
				 aria-hidden="true">
        		<path fill-rule="evenodd"
					  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
					  clip-rule="evenodd"
				/>
        	</svg>
        </span>
	{/if}
</div>
