@extends('public_layouts.app')

@section('content')

<section class="content-section min-h-[60vh] p-6">
    <h3 class="font-semibold text-xl mb-8 -mt-8 ml-1">Screen Reader Access</h3>

    <div class="overflow-x-auto border rounded-lg" id="main-content">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-slate-900 text-white text-left">
                    <th class="p-4">Screen Reader</th>
                    <th class="p-4">Website</th>
                    <th class="p-4">Free / Commercial</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="p-4 font-medium">Non Visual Desktop Access (NVDA)</td>
                    <td class="p-4">
                        <a href="http://www.nvda-project.org/" target="_blank" class="text-blue-500 hover:underline">
                            nvda-project.org
                        </a>
                    </td>
                    <td class="p-4 text-green-600 font-semibold">Free</td>
                </tr>
                <tr>
                    <td class="p-4 font-medium">Screen Access For All (SAFA)</td>
                    <td class="p-4">
                        <a href="http://safa-reader.software.informer.com/download/" target="_blank" class="text-blue-500 hover:underline">
                            safa-reader.software.informer.com
                        </a>
                    </td>
                    <td class="p-4 text-green-600 font-semibold">Free</td>
                </tr>
                <tr>
                    <td class="p-4 font-medium">System Access To Go</td>
                    <td class="p-4">
                        <a href="http://www.satogo.com/" target="_blank" class="text-blue-500 hover:underline">
                            satogo.com
                        </a>
                    </td>
                    <td class="p-4 text-green-600 font-semibold">Free</td>
                </tr>
                <tr>
                    <td class="p-4 font-medium">Windows Narrator (Windows only)</td>
                    <td class="p-4">
                        <a href="https://www.microsoft.com/enable/training/windowsxp" target="_blank" class="text-blue-500 hover:underline">
                            microsoft.com
                        </a>
                    </td>
                    <td class="p-4 text-green-600 font-semibold">Free</td>
                </tr>
                <tr>
                    <td class="p-4 font-medium">Hal</td>
                    <td class="p-4">
                        <a href="http://www.yourdolphin.co.uk/productdetail.asp?id=5" target="_blank" class="text-blue-500 hover:underline">
                            yourdolphin.co.uk
                        </a>
                    </td>
                    <td class="p-4 text-red-600 font-semibold">Commercial</td>
                </tr>
                <tr>
                    <td class="p-4 font-medium">JAWS</td>
                    <td class="p-4">
                        <a href="http://www.freedomscientific.com/jaws-hq.asp" target="_blank" class="text-blue-500 hover:underline">
                            freedomscientific.com
                        </a>
                    </td>
                    <td class="p-4 text-red-600 font-semibold">Commercial</td>
                </tr>
                <tr>
                    <td class="p-4 font-medium">Supernova</td>
                    <td class="p-4">
                        <a href="http://www.yourdolphin.co.uk/productdetail.asp?id=1" target="_blank" class="text-blue-500 hover:underline">
                            yourdolphin.co.uk
                        </a>
                    </td>
                    <td class="p-4 text-red-600 font-semibold">Commercial</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

@endsection