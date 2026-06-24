<style>
    .has-footer {
        padding-bottom: calc(64px + env(safe-area-inset-bottom, 0px));
    }
</style>

<?php
echo $activeTab;
if (!function_exists('userFootActive')) {
    function userFootActive($key, $active)
    {
        return $key === ($active ?? '');
    }
}
$borderClasses = "border-t-3 border-emerald-600";
$iconClasses = "text-emerald-600";

?>

<nav
    class="fixed inset-x-0 bottom-0 bg-white border-t border-[#F0F1F3] shadow-[0_-2px_12px_rgba(0,0,0,0.08)] pb-[calc(6px+env(safe-area-inset-bottom,0px))] z-[1000]">
    <div class="max-w-[460px] mx-auto flex ">
        <a class="flex flex-col flex-1 items-center justify-center gap-[3px] no-underline p-[6px_4px]  transition-colors duration-[120ms] min-h-[52px]  active:bg-gray-100 text-gray-400 active:text-gray-500 <?php if($activeTab == "barcode"){ echo $borderClasses; } ?>"
            href="/user/barcode" aria-label="barcode">
            <div class="w-[28px] h-[28px] grid place-items-center text-[20px] relative <?php if($activeTab == "barcode"){ echo $iconClasses; }?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <g fill="none" stroke="currentColor" stroke-linecap="round">
                        <path stroke-width="2"
                            d="M2.992 4.983v13.934m6.97-13.934v13.934m5.976-13.934v13.934m2.987-13.934v13.934" />
                        <path d="M5.48 4.483v14.934M7.47 4.483v14.934M21.413 4.483v14.934M13.446 4.483v14.934" />
                    </g>
                </svg>
            </div>
            <div class="text-[10px] font-[700] tracking-[0.1px] mt-[1px] <?php if($activeTab == "barcode"){ echo $iconClasses; }?>">บาร์โค้ด</div>
        </a>

        <a class="flex flex-col flex-1 items-center justify-center gap-[3px] no-underline p-[6px_4px]  transition-colors duration-[120ms] min-h-[52px]  active:bg-gray-100 text-gray-400 active:text-gray-500 <?php if($activeTab == "dashboard"){ echo $borderClasses; } ?>"
            href="/user" aria-label="Home">
            <div class="w-[28px] h-[28px] grid place-items-center text-[20px] relative <?php if($activeTab == "dashboard"){ echo $iconClasses; }?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.5">
                        <rect width="18.5" height="3" x="2.75" y="18.376" rx="1" />
                        <path
                            d="M11.04 3.15L3.27 7.4a1 1 0 0 0-.52.877v.997a.6.6 0 0 0 .6.6h17.3a.6.6 0 0 0 .6-.6v-.997a1 1 0 0 0-.52-.877l-7.77-4.25a2 2 0 0 0-1.92 0M5.25 9.874v8.51m13.5-8.51v8.51m-4.25-8.51v8.51m-5-8.51v8.51" />
                    </g>
                </svg>
            </div>
            <div class="text-[10px] font-[700] tracking-[0.1px] mt-[1px] <?php if($activeTab == "dashboard"){ echo $iconClasses; }?>">บัญชี</div>
        </a>

        <a class="flex flex-col flex-1 items-center justify-center gap-[3px] no-underline p-[6px_4px]  transition-colors duration-[120ms] min-h-[52px]  active:bg-gray-100 text-gray-400 active:text-gray-500 <?php if($activeTab == "profile"){ echo $borderClasses; } ?>"
            href="/user/profile" aria-label="profile">
            <div class="w-[28px] h-[28px] grid place-items-center text-[20px] relative <?php if($activeTab == "profile"){ echo $iconClasses; }?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                    <g fill="none" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linejoin="round"
                            d="M4 18a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2Z" />
                        <circle cx="12" cy="7" r="3" />
                    </g>
                </svg>
            </div>
            <div class="text-[10px] font-[700] tracking-[0.1px] mt-[1px] <?php if($activeTab == "profile"){ echo $iconClasses; }?>">โปรไฟล์</div>
        </a>
    </div>
</nav>