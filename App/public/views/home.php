<div x-data="wasteBankApp()" x-init=" $nextTick(() => { lucide.createIcons() })" class="min-h-screen">

    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100">
        <div class="max-w-2xl lg:max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-3">
                    <a href="/" class="h-full flex items-center justify-center">
                        <img src="assets/images/bru_gogreen_logo.png" alt="BRU Waste Bank"
                            class="h-[60%] hover:cursor-pointer">
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-4 lg:space-x-8 text-xs">
                    <button @click="scrollTo('about')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">รู้จักโครงการ</button>
                    <button @click="scrollTo('leaderboard')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">กระดานผู้นำ</button>
                    <button @click="scrollTo('waste-types')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">ขยะที่เปิดรับ</button>
                    <button @click="scrollTo('rewards')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">ของรางวัล</button>
                    <button @click=" scrollTo('video-guide')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">วิดีโอแนะนำ</button>
                    <?php if (!$isLogin): ?>
                        <button onclick="window.location.href='/login'"
                            class=" bg-green-600 text-white px-4 py-3 rounded-xl font-semibold cursor-pointer hover:scale-105 active:scale-98 hover:bg-green-700 active:bg-green-700">เข้าสู่ระบบ</button>
                    <?php else: ?>
                        <button onclick="window.location.href='/login'"
                            class=" bg-green-600 text-white px-4 py-3 rounded-xl font-semibold cursor-pointer hover:scale-105 active:scale-98 hover:bg-green-700 active:bg-green-700">เข้าใช้งาน</button>
                    <?php endif; ?>
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="isMenuOpen = !isMenuOpen" class="text-gray-600">
                        <i x-show="!isMenuOpen" data-lucide="menu" class="w-6 h-6"></i>
                        <i x-show="isMenuOpen" x-cloak data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="isMenuOpen" x-collapse x-cloak
            class="md:hidden bg-white border-b border-gray-100 px-4 pt-2 pb-6 space-y-3 shadow-lg absolute w-full">
            <button @click="scrollTo('about')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">รู้จักโครงการ</button>
            <button @click="scrollTo('leaderboard')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">กระดานผู้นำ</button>
            <button @click="scrollTo('waste-types')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">ขยะที่เปิดรับ</button>
            <button @click="scrollTo('rewards')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">ของรางวัล</button>
            <button @click="scrollTo('video-guide')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">วิดีโอแนะนำ</button>
            <?php if (!$isLogin): ?>
                <div class="pt-2">
                    <button onclick="window.location.href='/login'"
                        class="w-full bg-green-600 text-white px-4 py-3 rounded-xl font-semibold cursor-pointer hover:scale-105 active:scale-98 hover:bg-green-700 active:bg-green-700">เข้าสู่ระบบ</button>
                </div>
            <?php else: ?>
                <div class="pt-2">
                    <button onclick="window.location.href='/login'"
                        class="w-full bg-green-600 text-white px-4 py-3 rounded-xl font-semibold cursor-pointer hover:scale-105 active:scale-98 hover:bg-green-700 active:bg-green-700">เข้าใช้งาน</button>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <section id="about"
        class="scroll-mt-20 relative min-h-[calc(100vh-5rem)] overflow-hidden flex flex-col justify-center py-6 md:py-12">

        <div class="absolute inset-0 bg-gradient-to-b from-green-50/50 to-white -z-10"></div>
        <div class="absolute top-0 right-0 w-67 h-67 bg-green-400/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>

        <div class="max-w-2xl lg:max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 text-center">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold text-sm mb-8">
                <span class="relative flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                ระบบธนาคารขยะเปิดให้บริการแล้ว
            </div>
            <h1 class="text-4xl lg:text-6xl font-extrabold text-gray-900 tracking-tight mb-3 lg:mb-6">
                เปลี่ยนขยะให้เป็นแต้ม<br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-500">
                    สร้างโลกสีเขียวไปด้วยกัน
                </span>
            </h1>
            <p class="mt-2 lg:mt-4 text-md lg:text-xl text-gray-600 max-w-2xl mx-auto mb-5 lg:mb-10 leading-relaxed">
                เข้าร่วมโครงการธนาคารขยะ มหาวิทยาลัยราชภัฏบุรีรัมย์ คัดแยกขยะ สะสมแต้ม แลกของรางวัล
                และร่วมสร้างสังคมคาร์บอนต่ำ พร้อมเก็บชั่วโมงจิตอาสา
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <?php if (!$isLogin): ?>
                    <button onclick="window.location.href = '/login'"
                        class="px-8 py-4 bg-white hover:bg-gray-50 hover:cursor-pointer text-gray-700 border border-gray-200 rounded-xl font-bold text-lg shadow-sm transition-all hover:scale-105 active:scale-98">
                        เข้าสู่ระบบ
                    </button>
                    <button onclick="window.location.href = '/register'"
                        class="px-8 py-4 bg-green-600 hover:bg-green-700 hover:cursor-pointer text-white rounded-xl font-bold text-lg shadow-lg shadow-green-600/30 transition-all hover:scale-105 active:scale-98 flex items-center justify-center gap-2">
                        สมัครสมาชิก <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </button>
                <?php else: ?>
                    <button onclick="window.location.href = '/login'"
                        class="px-8 py-4 bg-green-600 hover:bg-green-700 hover:cursor-pointer text-white rounded-xl font-bold text-lg shadow-lg shadow-green-600/30 transition-all hover:scale-105 active:scale-98 flex items-center justify-center gap-2">
                        เข้าใช้งาน
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="max-w-2xl lg:max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-8 md:mt-12 relative z-10">
            <div
                class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 p-8 grid grid-cols-2 lg:grid-cols-4 gap-8 divide-y md:divide-y-0 md:divide-x divide-gray-100 border border-gray-100">
                <template x-for="(stat, index) in stats" :key="index">
                    <div :class="{'md:pt-0': index !== 0}" class="flex flex-col items-center text-center">
                        <div class="bg-gray-50 p-4 rounded-2xl mb-4" :class="stat.iconColor">
                            <i :data-lucide="stat.icon" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-3xl font-black text-gray-900 mb-1" x-text="stat.value"></h3>
                        <p class="text-sm text-gray-500 font-medium" x-text="stat.label"></p>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <section id="leaderboard" class="py-12 bg-white">
        <div class="max-w-2xl lg:max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center p-3 bg-yellow-100 rounded-full mb-4">
                    <i data-lucide="trophy" class="w-8 h-8 text-yellow-600"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4"
                    x-text="leaderboardType === 'faculty' ? 'กระดานผู้นำระดับคณะ' : (leaderboardType === 'major' ? 'กระดานผู้นำระดับสาขา' : 'กระดานผู้นำระดับบุคคล')">
                    กระดานผู้นำระดับคณะ</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg mb-8">
                    การแข่งขันเชิงสร้างสรรค์
                    เพื่อค้นหาสุดยอดคณะและบุคคลที่มีส่วนร่วมในการจัดการขยะและลดคาร์บอนได้มากที่สุด
                </p>
            </div>

            <div
                class="max-w-2xl lg:max-w-6xl rounded-full p-1 md:p-2 flex flex-row justify-between items-center gap-4">
                <div class="bg-gray-100 p-1 rounded-full flex justify-center relative shadow-inner">
                    <button @click="setLeaderboardType('faculty')"
                        :class="leaderboardType === 'faculty' ? 'bg-white shadow text-emerald-700 font-medium' : 'text-gray-400 hover:text-emerald-700 font-medium'"
                        class="px-3 md:px-6 py-2 rounded-full text-xs lg:text-sm transition-all whitespace-nowrap text-center flex gap-2 items-center cursor-pointer">
                        <i data-lucide="building-2" class="w-4 h-4"></i><span class="hidden md:inline">ระดับคณะ</span>
                    </button>
                    <button @click="setLeaderboardType('major')"
                        :class="leaderboardType === 'major' ? 'bg-white shadow text-emerald-700 font-medium' : 'text-gray-400 hover:text-emerald-700 font-medium'"
                        class="px-3 md:px-6 py-2 rounded-full text-xs lg:text-sm transition-all whitespace-nowrap text-center flex gap-2 items-center cursor-pointer">
                        <i data-lucide="school" class="w-4 h-4"></i><span class="hidden md:inline">ระดับสาขา</span>

                    </button>
                    <button @click="setLeaderboardType('member')"
                        :class="leaderboardType === 'member' ? 'bg-white shadow text-emerald-700 font-medium' : 'text-gray-400 hover:text-emerald-700 font-medium'"
                        class="px-3 md:px-6 py-2 rounded-full text-xs lg:text-sm transition-all whitespace-nowrap text-center flex gap-2 items-center cursor-pointer">
                        <i data-lucide="users-round" class="w-4 h-4"></i><span
                            class="hidden md:inline">ระดับบุคคล</span>
                    </button>
                </div>

                <div class="bg-gray-100 p-1 rounded-full flex justify-center relative shadow-inner">
                    <button @click="setSortType('point')"
                        :class="{'bg-white shadow text-sky-600': sortType === 'point', 'text-gray-400 hover:text-sky-600': sortType !== 'point'}"
                        class="px-3 lg:px-6 py-2 rounded-full text-xs lg:text-sm font-medium transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="trash-2" class="w-4 h-4"></i> <span class="hidden lg:inline">แต้มขยะ</span>
                    </button>
                    <button @click="setSortType('event')"
                        :class="{'bg-white shadow text-purple-600': sortType === 'event', 'text-gray-400 hover:text-purple-600': sortType !== 'event'}"
                        class="px-3 md:px-6 py-2 rounded-full text-xs lg:text-sm font-medium transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="smile" class="w-4 h-4"></i> <span class="hidden lg:inline">แต้มกิจกรรม</span>
                    </button>
                    <button @click="setSortType('social')"
                        :class="{'bg-white shadow text-red-600': sortType === 'social', 'text-gray-400 hover:text-red-700': sortType !== 'social'}"
                        class="px-3 md:px-6 py-2 rounded-full text-xs lg:text-sm font-medium transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="users" class="w-4 h-4"></i> <span class="hidden lg:inline">แต้มสังคม</span>
                    </button>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-center items-end gap-3 md:gap-6 mb-8 md:mb-16 px-4 md:px-12 mt-18 md:mt-24"
                x-show="activeLeaderboard?.length >= 3">
                <div class="w-full md:w-1/3 order-2 md:order-1 relative group mt-16 md:mt-0">
                    <div class="absolute -top-12 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center">
                        <div class="relative">
                            <div
                                class="w-25 h-25 md:w-22 md:h-22 md:w-24 md:h-24 rounded-full border-4 border-white shadow-lg bg-gray-800 flex items-center justify-center overflow-hidden">
                                <template x-if="leaderboardType === 'member'">
                                    <span class="text-4xl font-bold text-blue-300"
                                        x-text="getFirstThaiChar(activeLeaderboard[1]?.name) || ''"></span>
                                </template>
                                <div x-show="leaderboardType !== 'member'">
                                    <i data-lucide="building-2" class="w-10 h-10 md:w-10 md:h-10 text-blue-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-2xl shadow-lg border border-gray-100 pt-13 md:pt-17 pb-8 px-6 text-center transform md:-translate-y-2 transition-transform group-hover:-translate-y-4">
                        <span
                            class="inline-block px-3 py-1 bg-slate-200 text-slate-600 text-[8px] font-bold rounded-full mb-4">
                            อันดับที่ 2
                        </span>
                        <h3 class="font-bold text-gray-900 text-lg lg:text-xl line-clamp-1"
                            x-text="activeLeaderboard[1]?.name || '...'"></h3>
                        <template
                            x-if="leaderboardType === 'member' && (activeLeaderboard[0]?.role == 1 || activeLeaderboard[0]?.role == 2)">
                            <div>
                                <p class="text-xs text-gray-500 transition-all duration-300 ease-out transform">
                                    คณะ: <span x-text="activeLeaderboard[0]?.fname"></span>
                                </p>
                                <p class="text-xs text-gray-500 transition-all duration-300 ease-out transform">
                                    สาขา: <span x-text="activeLeaderboard[0]?.mname"></span>
                                </p>
                            </div>
                        </template>
                        <template x-if="leaderboardType === 'major'">
                            <span class="text-gray-500 text-xs mb-1 line-clamp-1"
                                x-text="activeLeaderboard[1]?.faculty_name || '...'">
                            </span>
                        </template>
                        <template
                            x-if="(leaderboardType === 'faculty' || leaderboardType === 'major') && sortType === 'point'">
                            <div>
                                <p class="text-gray-500 text-xs mb-1 line-clamp-1"
                                    x-text="`ขยะสะสม ${activeLeaderboard[1]?.weight}`">
                                </p>
                                <p class="text-gray-500 text-xs mb-1 line-clamp-1"
                                    x-text="`การลดคาร์บอน ${activeLeaderboard[1]?.co2e}`">
                                </p>
                            </div>
                        </template>
                        <div class="text-3xl font-black"
                            :class="sortType === 'point' ? 'text-sky-500' : sortType === 'social' ? 'text-red-600' : 'text-purple-600'"
                            x-text="getSortValue(activeLeaderboard[1])">
                        </div>
                        <div class="text-xs text-gray-400 font-medium uppercase tracking-wider" x-text="getSortLabel()">
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/3 order-1 md:order-2 relative group mt-16 md:mt-0">
                    <div
                        class="absolute -top-25 md:-top-30 lg:-top-35 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center">
                        <i data-lucide="crown" class="w-8 h-8 text-yellow-500 mb-1 drop-shadow-md animate-bounce "></i>
                        <div class="relative">
                            <div
                                class="w-25 h-25 md:w-24 md:h-24 lg:w-32 lg:h-32 rounded-full border-4 border-yellow-400 shadow-xl bg-gray-900 flex items-center justify-center overflow-hidden ring-4 ring-white">
                                <template x-if="leaderboardType === 'member'">
                                    <span class="text-5xl font-bold text-yellow-400"
                                        x-text="getFirstThaiChar(activeLeaderboard[0]?.name) || '?'"></span>
                                </template>
                                <div x-show="leaderboardType !== 'member'">
                                    <i data-lucide="trophy" class="w-10 h-10 md:w-14 md:h-14 text-yellow-500"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div
                        class="bg-white rounded-t-3xl rounded-b-2xl border border-gray-100 shadow-xl pt-10 md:pt-15 lg:pt-17 pb-8 px-6 text-center transform md:-translate-y-6 transition-transform group-hover:-translate-y-8 relative">
                        <span
                            class="inline-block px-3 py-1 bg-yellow-300/70 text-yellow-700 text-[8px] font-bold rounded-full mb-4">
                            อันดับที่ 1
                        </span>
                        <h3 class="font-bold text-gray-900 text-lg lg:text-2xl line-clamp-1"
                            x-text="activeLeaderboard[0]?.name || '...'">
                        </h3>
                        <template
                            x-if="leaderboardType === 'member' && (activeLeaderboard[0]?.role == 1 || activeLeaderboard[0]?.role == 2)">
                            <div class="my-1">
                                <p class="text-xs text-gray-500 transition-all duration-300 ease-out transform">
                                    คณะ: <span x-text="activeLeaderboard[0]?.fname"></span>
                                </p>
                                <p class="text-xs text-gray-500 transition-all duration-300 ease-out transform">
                                    สาขา: <span x-text="activeLeaderboard[0]?.mname"></span>
                                </p>
                            </div>
                        </template>

                        <template x-if="leaderboardType === 'major'">
                            <span class="text-gray-500 text-xs mb-1 line-clamp-1"
                                x-text="activeLeaderboard[0]?.faculty_name || '...'">
                            </span>
                        </template>
                        <template
                            x-if="(leaderboardType === 'faculty' || leaderboardType === 'major') && sortType === 'point'">
                            <div>
                                <p class="text-gray-500 text-xs mb-1 line-clamp-1"
                                    x-text="`ขยะสะสม ${activeLeaderboard[1]?.weight}`">
                                </p>
                                <p class="text-gray-500 text-xs mb-1 line-clamp-1"
                                    x-text="`การลดคาร์บอน ${activeLeaderboard[1]?.co2e}`">
                                </p>
                            </div>
                        </template>
                        <div class="text-3xl lg:text-3xl font-black"
                            :class="sortType === 'point' ? 'text-sky-500' : sortType === 'social' ? 'text-red-600' : 'text-purple-600'"
                            x-text="getSortValue(activeLeaderboard[0])">
                        </div>
                        <div class="text-xs text-gray-400 font-medium uppercase tracking-wider" x-text="getSortLabel()">
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/3 order-3 relative group mt-16 md:mt-0">
                    <div class="absolute -top-6 lg:-top-10 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center">
                        <div class="relative">
                            <div
                                class="w-20 h-20 lg:w-24 lg:h-24 rounded-full border-4 border-white shadow-lg bg-gray-800 flex items-center justify-center overflow-hidden">
                                <template x-if="leaderboardType === 'member'">
                                    <span class="text-4xl font-bold text-orange-300"
                                        x-text="getFirstThaiChar(activeLeaderboard[2]?.name) || '?'"></span>
                                </template>
                                <div x-show="leaderboardType !== 'member'">
                                    <i data-lucide="medal" class="w-10 h-10 text-orange-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-2xl shadow-lg border border-gray-100 pt-15 pb-6 px-6 text-center transition-transform group-hover:-translate-y-2">
                        <span
                            class="inline-block px-3 py-1 bg-red-800/80 text-white text-[8px] font-bold rounded-full mb-6">
                            อันดับที่ 3
                        </span>

                        <h3 class="font-bold text-gray-900 text-lg  line-clamp-1"
                            x-text="activeLeaderboard[2]?.name || '...'">
                        </h3>
                        <template
                            x-if="leaderboardType === 'member' && (activeLeaderboard[0]?.role == 1 || activeLeaderboard[0]?.role == 2)">
                            <div>
                                <p class="text-xs text-gray-500 transition-all duration-300 ease-out transform">
                                    คณะ: <span x-text="activeLeaderboard[0]?.fname"></span>
                                </p>
                                <p class="text-xs text-gray-500 transition-all duration-300 ease-out transform">
                                    สาขา: <span x-text="activeLeaderboard[0]?.mname"></span>
                                </p>
                            </div>
                        </template>
                        <template x-if="leaderboardType === 'major'">
                            <span class="text-gray-500 text-xs mb-1 line-clamp-1"
                                x-text="activeLeaderboard[2]?.faculty_name || '...'">
                            </span>
                        </template>
                        <template
                            x-if="(leaderboardType === 'faculty' || leaderboardType === 'major') && sortType === 'point'">
                            <div>
                                <p class="text-gray-500 text-xs mb-1 line-clamp-1"
                                    x-text="`ขยะสะสม ${activeLeaderboard[1]?.weight}`">
                                </p>
                                <p class="text-gray-500 text-xs mb-1 line-clamp-1"
                                    x-text="`การลดคาร์บอน ${activeLeaderboard[1]?.co2e}`">
                                </p>
                            </div>
                        </template>
                        <div class="text-3xl font-black"
                            :class="sortType === 'point' ? 'text-sky-500' : sortType === 'social' ? 'text-red-600' : 'text-purple-600'"
                            x-text="getSortValue(activeLeaderboard[2])">
                        </div>
                        <div class="text-xs text-gray-400 font-medium uppercase tracking-wider" x-text="getSortLabel()">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white w-full rounded-2xl shadow-sm border border-gray-200 overflow-x-auto"
                x-show="activeLeaderboard?.length > 3">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-4 py-4 text-gray-600 font-bold text-sm uppercase w-5 tracking-wider">อันดับ
                            </th>
                            <th class="px-4 py-4 text-gray-600 font-bold text-sm uppercase tracking-wider"
                                x-text="leaderboardType === 'faculty' ? 'คณะ' : (leaderboardType === 'major' ? 'สาขา' : 'ชื่อ-สกุล')">
                            </th>
                            <th class="px-4 py-4 text-gray-600 font-bold text-sm uppercase tracking-wider text-end"
                                x-text="getSortLabel()"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="(item, index) in activeLeaderboard?.slice(3)" :key="item.rank">
                            <tr class="hover:bg-blue-50/50 transition-colors group">
                                <td class="px-6 py-4 text-center">
                                    <span class="text-gray-400 font-bold text-md w-6 text-center font-mono"
                                        x-text="item.rank"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm md:text-base group-hover:text-blue-700 transition-colors"
                                            x-text="item.name"></h4>
                                        <template x-if="leaderboardType !== 'member'">
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500 transition-all duration-300 ease-out transform">
                                                    ปริมาณขยะ: <span x-text="item.weight"></span>
                                                </p>
                                                <p
                                                    class="text-xs text-gray-500 transition-all duration-300 ease-out transform">
                                                    ลดคาร์บอน: <span x-text="item.co2e"></span> CO₂e
                                                </p>
                                            </div>
                                        </template>
                                        <template
                                            x-if="leaderboardType === 'member' && (item.role === 1 || item.role === 2)">
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500 transition-all duration-300 ease-out transform">
                                                    คณะ: <span x-text="item.fname"></span>
                                                </p>
                                                <p
                                                    class="text-xs text-gray-500 transition-all duration-300 ease-out transform">
                                                    สาขา: <span x-text="item.mname"></span>
                                                </p>
                                            </div>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-end">
                                    <div class="text-md md:text-lg font-black transition-all duration-300 ease-in-out transform"
                                        :class="sortType === 'point' ? 'text-sky-500' : sortType === 'social' ? 'text-red-600' : 'text-purple-600'"
                                        x-text="getSortValue(item)"></div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="p-4 bg-gray-50 text-center text-xs text-gray-500 border-t border-gray-100 italic">
                    แสดงข้อมูลอันดับ 4 - 10 จากทั้งหมด
                </div>
            </div>

        </div>
    </section>

    <section id="member-stats" class="py-12 bg-white">
        <div class="max-w-2xl lg:max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center p-3 bg-red-100 rounded-full mb-4">
                    <i data-lucide="bar-chart-3" class="w-8 h-8 text-red-600"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    สถิติการเข้าร่วม
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg mb-8">
                    ภาพรวมจำนวนสมาชิกที่เข้าร่วมโครงการธนาคารขยะในแต่ละคณะและสาขา
                </p>
            </div>

            <div
                class="max-w-2xl lg:max-w-6xl rounded-full p-2 md:p-3 flex flex-row justify-between items-center gap-4">
                <div class="bg-gray-200 p-1 rounded-full flex flex-wrap justify-center relative shadow-inner">
                    <button @click="setMemberLeaderboardType('faculty')"
                        :class="memberLeaderboardType === 'faculty' ? 'bg-white shadow text-red-600 font-medium' : 'text-gray-500 hover:text-red-600'"
                        class="px-3 lg:px-6 py-2 rounded-full text-xs lg:text-sm transition-all whitespace-nowrap text-center flex gap-2 items-center cursor-pointer">
                        <i data-lucide="building-2" class="w-4 h-4"></i><span class="hidden md:inline">อันดับคณะ</span>

                    </button>
                    <button @click="setMemberLeaderboardType('major')"
                        :class="memberLeaderboardType === 'major' ? 'bg-white shadow text-red-600 font-medium' : 'text-gray-500 hover:text-red-600'"
                        class="px-3 lg:px-6 py-2 rounded-full text-xs lg:text-sm transition-all whitespace-nowrap text-center flex gap-2 items-center cursor-pointer">
                        <i data-lucide="school" class="w-4 h-4"></i><span class="hidden md:inline">อันดับสาขา</span>

                    </button>
                </div>

                <div class="bg-gray-200 p-1 rounded-full flex flex-wrap justify-center relative shadow-inner">
                    <button @click="setMemberSortType('member')"
                        :class="memberSortType === 'member' ? 'bg-white shadow text-red-600 font-medium' : 'text-gray-500 hover:text-red-600'"
                        class="px-3 lg:px-6 py-2 rounded-full text-xs lg:text-sm transition-all whitespace-nowrap text-center flex gap-2 items-center cursor-pointer">
                        <i data-lucide="graduation-cap" class="w-4 h-4"></i><span
                            class="hidden lg:inline">นักศึกษา</span>

                    </button>
                    <button @click="setMemberSortType('professor')"
                        :class="memberSortType === 'professor' ? 'bg-white shadow text-red-600 font-medium' : 'text-gray-500 hover:text-red-600'"
                        class="px-3 lg:px-6 py-2 rounded-full text-xs lg:text-sm transition-all whitespace-nowrap text-center flex gap-2 items-center cursor-pointer">
                        <i data-lucide="contact-round" class="w-4 h-4"></i><span class="hidden lg:inline">อาจารย์</span>

                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-4 py-4 text-gray-600 font-bold text-sm uppercase w-5 tracking-wider">อันดับ
                            </th>
                            <th class="px-4 py-4 text-gray-600 font-bold text-sm uppercase tracking-wider"
                                x-text="memberLeaderboardType === 'faculty' ? 'คณะ' : 'สาขา'"></th>
                            <th class="px-4 py-4 text-gray-600 font-bold text-sm uppercase tracking-wider text-end"
                                x-text="getMemberSortLabel()"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="(item, index) in activeMemberLeaderboard" :key="item.rank">
                            <tr class="hover:bg-blue-50/50 transition-colors group"
                                x-show="index < memberLeaderboardVisibleCount" x-transition>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-gray-400 font-bold text-lg w-6 text-center font-mono"
                                        x-text="item.rank"></span>
                                </td>
                                <td class="px-4 py-4">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm md:text-base group-hover:text-blue-700 transition-colors"
                                            x-text="item.name"></h4>
                                        <template x-if="memberLeaderboardType === 'major'">
                                            <p class="text-xs text-gray-500" x-text="item.faculty_name"></p>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-end">
                                    <div class="text-lg font-black text-red-600" x-text="item.count"></div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="!activeMemberLeaderboard || activeMemberLeaderboard.length === 0">
                            <tr>
                                <td colspan="3" class="text-center py-10 text-gray-500">
                                    ไม่พบข้อมูล
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-center gap-1 mt-2">
                <button @click="memberLeaderboardVisibleCount = Math.max(memberLeaderboardVisibleCount - 5, 5)"
                    x-show="activeMemberLeaderboard && memberLeaderboardVisibleCount > 5" x-cloak
                    class="mt-8 flex items-center justify-center w-full border border-gray-100 hover:border-gray-100 border-1 rounded-2xl cursor-pointer py-1 transition-all shadow-xs hover:shadow-md">
                    <div class="text-sm font-medium text-green-700 hover:text-green-800 flex items-center gap-2">

                        <span class="flex items-center gap-1 cursor-pointer">
                            <i data-lucide="chevron-up" class="w-4 h-4"></i>
                            ดูน้อยลง
                        </span>
                    </div>
                </button>
                <button
                    @click="memberLeaderboardVisibleCount = Math.min(memberLeaderboardVisibleCount + 5, activeMemberLeaderboard.length)"
                    x-show="activeMemberLeaderboard && memberLeaderboardVisibleCount < activeMemberLeaderboard.length"
                    x-cloak
                    class="mt-8 flex items-center justify-center w-full border border-gray-100 hover:border-gray-100 border-1 rounded-2xl cursor-pointer py-1 transition-all shadow-xs hover:shadow-md">
                    <div class="text-sm font-medium text-blue-700 hover:text-blue-800 flex items-center gap-2 ">
                        <div class="text-sm font-medium text-green-700 hover:text-green-800 flex items-center gap-2">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            <span class="flex items-center gap-1 cursor-pointer">
                                ดูเพิ่มเติม
                            </span>
                        </div>
                </button>
            </div>

        </div>
    </section>

    <section id="waste-types" class="bg-white py-12">
        <div class="max-w-2xl lg:max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 md:mb-12">
                <div class="inline-flex items-center justify-center p-3 bg-green-100 rounded-full mb-4">
                    <i data-lucide="recycle" class="w-8 h-8 text-green-600"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">ประเภทขยะที่รับซื้อ</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    ประเภทและราคาของขยะที่โครงการรับซื้อ ณ ปัจจุบัน
                </p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="(category, index) in wasteCategories" :key="category.waste_category_id">
                    <div x-show="isWasteTableExpanded || (isLgScreen ? index < 3 : index < 2)"
                        class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all flex flex-col">
                        <div class="flex justify-between items-center text-sm mb-4">
                            <h3 class="font-bold text-lg text-gray-800"
                                x-text="category.waste_category_name || 'อื่น ๆ'">
                            </h3>
                            <span>แต้ม/กิโล</span>
                        </div>
                        <ul class="space-y-4 flex-grow">
                            <template x-for="wasteType in category.waste_types" :key="wasteType.waste_type_id">
                                <li class="flex justify-between items-center text-sm">
                                    <div class="flex gap-1 items-center">
                                        <div class="w-2 h-2 rounded-full"
                                            :class="wasteType.waste_type_active == 1 ? 'bg-green-300':'bg-gray-200'">
                                        </div>
                                        <span class="text-gray-600 font-normal"
                                            x-text="wasteType.waste_type_name"></span>
                                    </div>
                                    <span class="font-semibold text-sky-600"
                                        x-text="`${parseInt(wasteType.waste_type_price * 10).toLocaleString()} แต้ม`"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>
            </div>
            <button @click="isWasteTableExpanded = !isWasteTableExpanded" x-show="wasteCategories.length > 3"
                class="mt-8 flex items-center justify-center w-full border border-gray-100 hover:border-gray-100 border-1 rounded-2xl cursor-pointer py-1 transition-all shadow-xs hover:shadow-md">
                <div class="text-sm font-medium text-green-700 hover:text-green-800 flex items-center gap-2 ">
                    <span x-show="!isWasteTableExpanded" class="flex items-center gap-1 cursor-pointer">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        ดูเพิ่มเติม
                    </span>
                    <span x-show="isWasteTableExpanded" class="flex items-center gap-1 cursor-pointer">
                        <i data-lucide="chevron-up" class="w-4 h-4"></i>
                        ย่อลง
                    </span>
                </div>
            </button>
        </div>
    </section>

    <section id="rewards" class="min-h-[calc(80vh)] bg-white py-12">
        <div class="max-w-2xl lg:max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 md:mb-12">
                <div class="inline-flex items-center justify-center p-3 bg-sky-200 rounded-full mb-4">
                    <i data-lucide="gift" class="w-8 h-8 text-sky-600"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">ของรางวัลที่แลกได้</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    ใช้แต้มขยะที่คุณสะสม มาแลกรับของรางวัลจากศูนย์ใหญ่ได้ทันที
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="(reward, index) in rewards" :key="reward.donation_item_id">
                    <div x-show="isRewardTableExpanded || (isLgScreen ? index < 6 : index < 4)"
                        class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl transition-all hover:-translate-y-1 group flex flex-col">
                        <div
                            class="w-full h-40 bg-gray-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-105 transition-transform overflow-hidden">
                            <img :src="reward.donation_item_image ? `assets/images/donation_items/${reward.donation_item_image}` : 'https://placehold.co/400x400/e2e8f0/a0aec0?text=BRU'"
                                :alt="reward.donation_item_name" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-bold text-lg text-gray-900 mb-1" x-text="reward.donation_item_name"></h3>
                        </div>

                        <div class="flex items-center justify-center pt-4 border-t border-gray-50">
                            <template
                                x-if="reward.donation_item_discount_point && reward.donation_item_discount_point > 0">
                                <div class="flex items-baseline gap-2">
                                    <span class="font-bold text-gray-400 line-through"
                                        x-text="parseInt(reward.donation_item_redeem_point).toLocaleString()"></span>
                                    <span class="font-bold text-green-600 text-xl"
                                        x-text="`${parseInt(reward.donation_item_discount_point).toLocaleString()} แต้ม`"></span>
                                </div>
                            </template>
                            <template
                                x-if="!reward.donation_item_discount_point || reward.donation_item_discount_point == 0">
                                <div class="font-bold text-green-600 text-center text-xl"><span
                                        x-text="`${parseInt(reward.donation_item_redeem_point).toLocaleString()} แต้ม`"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            <button @click="isRewardTableExpanded = !isRewardTableExpanded"
                x-show="rewards.length > (isLgScreen ? 6 : 4)" x-cloak
                class="mt-8 flex items-center justify-center w-full border border-gray-100 hover:border-gray-100 border-1 rounded-2xl cursor-pointer py-1 transition-all shadow-xs hover:shadow-md">
                <div class="text-sm font-medium text-green-700 hover:text-green-800 flex items-center gap-2 ">
                    <span x-show="!isRewardTableExpanded" class="flex items-center gap-1 cursor-pointer">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        ดูเพิ่มเติม
                    </span>
                    <span x-show="isRewardTableExpanded" x-cloak class="flex items-center gap-1 cursor-pointer">
                        <i data-lucide="chevron-up" class="w-4 h-4"></i>
                        ย่อลง
                    </span>
                </div>
            </button>
        </div>
    </section>

    <section id="video-guide"
        class="scroll-mt-20 bg-white min-h-[calc(80vh)] overflow-hidden flex flex-col justify-center py-6 md:py-12">
        <div class="max-w-2xl lg:max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 md:mb-12">
                <div class="inline-flex items-center justify-center p-3 bg-red-100 rounded-full mb-4">
                    <i data-lucide="square-play" class="w-8 h-8 text-red-600"></i>
                </div>
                <!-- <h2 class="text-3xl font-bold text-gray-900 mb-4">วิดีโอแนะนำระบบ</h2> -->
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    วิดิโอแนะนำกิจกรรม BRU Go Green
                </p>
            </div>
            <div class="max-w-2xl lg:max-w-6xl mx-auto w-full">
                <div class="w-full aspect-video rounded-2xl overflow-hidden shadow-xl border border-gray-200 bg-black">
                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/s-OtnUQglrs?si=mltqiwJ43FM2pud1"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
        <div class="max-w-2xl lg:max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 items-center">
                <div class="flex flex-col justify-center items-center">
                    <div class="flex items-center gap-3 mb-4">
                        <div>
                            <img class="h-10" src="assets/images/waste_bankFullLogo.png" alt="">
                            <!-- <h1 class="font-bold text-lg text-white">BRU Waste Bank</h1> -->
                        </div>
                        <div>
                            <img class="h-10" src="assets/images/bru_gogreen_logo.png" alt="">
                            <!-- <h1 class="font-bold text-lg text-white">BRU Waste Bank</h1> -->
                        </div>
                    </div>
                    <p class="text-sm">
                        โครงการธนาคารขยะ มหาวิทยาลัยราชภัฏบุรีรัมย์<br />
                        สร้างสังคมคาร์บอนต่ำอย่างยั่งยืน
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm">
                        © <span x-text="new Date().getFullYear()"></span>
                        มหาวิทยาลัยราชภัฏบุรีรัมย์.<br />สงวนลิขสิทธิ์.
                    </p>
                </div>
                <div class="flex justify-center md:justify-end gap-4">
                    <button class="hover:text-white transition-all cursor-pointer">ติดต่อแอดมิน</button>
                    <button class="hover:text-white transition-all cursor-pointer">นโยบายความเป็นส่วนตัว</button>
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('wasteBankApp', () => ({
            isMenuOpen: false,
            stats: [
                { label: 'ผู้เข้าร่วม', value: '0', icon: 'users', iconColor: 'text-green-500' },
                { label: 'นักศึกษา', value: '0', icon: 'graduation-cap', iconColor: 'text-green-500' },
                { label: 'อาจารย์/บุคลากร', value: '0', icon: 'id-card-lanyard', iconColor: 'text-green-500' },
                { label: 'แต้มที่แจกจ่ายแล้ว', value: '0', icon: 'award', iconColor: 'text-yellow-500' },
            ],
            wasteCategories: [],
            isWasteTableExpanded: false,
            isRewardTableExpanded: false,
            rewards: [],
            leaderboardType: 'faculty',
            facultyLeaderboard: [],
            majorLeaderboard: [],
            memberLeaderboard: [],
            sortType: 'point',
            memberLeaderboardType: 'faculty', // or 'major'
            memberSortType: 'member', // or 'professor', 'employee'
            facultyMemberLeaderboard: [],
            majorMemberLeaderboard: [],
            memberLeaderboardVisibleCount: 5,
            isLgScreen: false,

            init() {
                this.isLgScreen = window.innerWidth >= 1024;
                window.addEventListener('resize', () => {
                    this.isLgScreen = window.innerWidth >= 1024;
                });

                this.fetchStats();
                this.fetchWasteGroups();
                this.fetchRewards();
                this.fetchAllLeaderboards();
                this.fetchMemberLeaderboards();
            },

            async fetchStats() {
                try {
                    const response = await fetch('/api/statistics');
                    const result = await response.json();
                    if (result.success && result.data) {
                        const data = result.data;
                        this.stats[0].value = parseFloat(data.member_count || 0).toLocaleString();
                        this.stats[1].value = parseFloat(data.user_count || 0).toLocaleString();
                        this.stats[2].value = parseFloat(data.professor_employee_count || 0).toLocaleString();
                        this.stats[3].value = parseInt(data.total_point || 0).toLocaleString();
                    }
                } catch (error) {
                    console.error('Error fetching stats:', error);
                }
            },

            async fetchWasteGroups() {
                try {
                    const response = await fetch('/api/waste_types/groups');
                    const result = await response.json();
                    if (result.success && result.data) {
                        this.wasteCategories = result.data;
                    }
                } catch (error) {
                    console.error('Error fetching waste groups:', error);
                }
            },

            async fetchRewards() {
                try {
                    const response = await fetch('/api/donations/items/available');
                    const result = await response.json();
                    if (result.success && result.data) {
                        this.rewards = result.data;
                    }
                } catch (error) {
                    console.error('Error fetching rewards:', error);
                }
            },

            scrollTo(id) {
                const el = document.getElementById(id);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth' });
                    this.isMenuOpen = false;
                }
            },

            getFirstThaiChar(name) {
                if (!name || name.length === 0) return '?';
                const leadingVowels = ['เ', 'แ', 'โ', 'ใ', 'ไ'];
                if (leadingVowels.includes(name.charAt(0)) && name.length > 1) {
                    return name.charAt(1);
                }
                return name.charAt(0);
            },

            getSortValue(item) {
                if (!item) return '';
                switch (this.sortType) {
                    case 'goodness':
                        return item.goodness;
                    case 'social':
                        return item.social;
                    case 'event':
                        return item.event;
                    case 'point':
                    default:
                        return item.points;
                }
            },

            getSortLabel() {
                switch (this.sortType) {
                    case 'goodness':
                        return 'แต้มความดี';
                    case 'social':
                        return 'แต้มสังคม';
                    case 'event':
                        return 'แต้มกิจกรรม';
                    case 'point':
                    default:
                        return 'แต้มขยะ';
                }
            },

            get activeLeaderboard() {
                if (this.leaderboardType === 'faculty') return this.facultyLeaderboard;
                if (this.leaderboardType === 'major') return this.majorLeaderboard;
                return this.memberLeaderboard;
            },


            setLeaderboardType(type) {
                this.leaderboardType = type;
                this.$nextTick(() => {
                    if (window.lucide) {
                        lucide.createIcons();
                    }
                });
            },

            setSortType(type) {
                this.sortType = type;
                this.fetchAllLeaderboards();
                this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
            },

            async fetchAllLeaderboards() {
                try {
                    const response = await fetch(`/api/leaders/all?limit=10&page=1&sort=${this.sortType}`);
                    const result = await response.json();
                    if (result.success && result.data) {
                        // Faculty
                        this.facultyLeaderboard = result.data.faculty.map((item, index) => ({
                            rank: index + 1,
                            name: `คณะ ${item.faculty_name}`,
                            points: `${parseInt(item.total_point).toLocaleString()}`,
                            goodness: `${parseInt(item.total_goodness).toLocaleString()}`,
                            event: `${parseInt(item.total_event).toLocaleString()}`,
                            social: `${parseInt(item.total_social).toLocaleString()}`,
                            weight: `${parseFloat(item.total_weight).toLocaleString()} กก.`,
                            co2e: `${parseFloat(item.total_co2e).toLocaleString()} CO2e.`,
                        }));

                        // Major
                        this.majorLeaderboard = result.data.major.map((item, index) => ({
                            rank: index + 1,
                            name: item.major_name,
                            faculty_name: `คณะ ${item.faculty_name}`,
                            points: `${parseInt(item.total_point).toLocaleString()}`,
                            event: `${parseInt(item.total_event).toLocaleString()}`,
                            goodness: `${parseInt(item.total_goodness).toLocaleString()}`,
                            social: `${parseInt(item.total_social).toLocaleString()}`,
                            weight: `${parseFloat(item.total_weight).toLocaleString()} กก.`,
                            co2e: `${parseFloat(item.total_co2e).toLocaleString()} CO2e.`,
                        }));

                        // Member
                        this.memberLeaderboard = result.data.member.map((item, index) => ({
                            rank: index + 1,
                            name: item.member_name || item.name || 'ไม่ระบุชื่อ',
                            points: `${parseInt(item.total_point).toLocaleString()}`,
                            event: `${parseInt(item.total_event).toLocaleString()}`,
                            goodness: `${parseInt(item.total_goodness).toLocaleString()}`,
                            weight: `${parseFloat(item.total_weight).toLocaleString()} กก.`,
                            social: `${parseInt(item.total_social).toLocaleString()}`,
                            co2e: `${parseFloat(item.total_co2e).toLocaleString()} กก.`,
                            fname: item.faculty_name || 'ไม่ได้ระบุคณะ',
                            mname: item.major_name || 'ไม่ได้ระบุสาขา',
                            role: parseInt(item.role_id)

                        }));
                        console.log(this.facultyLeaderboard)
                        setTimeout(() => { if (window.lucide) lucide.createIcons(); }, 100);
                    }
                } catch (error) {
                    console.error('Error fetching leaderboard:', error);
                }
            },

            get activeMemberLeaderboard() {
                if (this.memberLeaderboardType === 'faculty') return this.facultyMemberLeaderboard;
                return this.majorMemberLeaderboard;
            },

            setMemberLeaderboardType(type) {
                this.memberLeaderboardType = type;
                this.$nextTick(() => {
                    if (window.lucide) {
                        lucide.createIcons();
                    }
                });
            },

            setMemberSortType(type) {
                this.memberSortType = type;
                this.fetchMemberLeaderboards();
                this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
            },

            async fetchMemberLeaderboards() {
                try {
                    const facultyResponse = await fetch(`/api/faculties?show_branch=false&sort_by=${this.memberSortType}`);
                    const facultyResult = await facultyResponse.json();
                    if (facultyResult.success && facultyResult.data) {
                        this.facultyMemberLeaderboard = facultyResult.data.map((item, index) => ({
                            rank: index + 1,
                            name: `คณะ ${item.faculty_name}`,
                            count: this.getMemberSortValue(item, 'faculty'),
                        }));
                    }

                    const majorResponse = await fetch(`/api/majors?sort_by=${this.memberSortType}`);
                    const majorResult = await majorResponse.json();
                    if (majorResult.success && majorResult.data) {
                        this.majorMemberLeaderboard = majorResult.data.map((item, index) => ({
                            rank: index + 1,
                            name: `สาขา ${item.major_name}`,
                            faculty_name: `คณะ ${item.faculty_name}`,
                            count: this.getMemberSortValue(item, 'major'),
                        }));
                    }

                    setTimeout(() => { if (window.lucide) lucide.createIcons(); }, 100);
                } catch (error) {
                    console.error('Error fetching member leaderboard:', error);
                }
            },

            getMemberSortValue(item, type) {
                if (!item) return '0';
                let count = 0;
                switch (this.memberSortType) {
                    case 'member':
                        count = (type === 'faculty') ? (item.user_count ?? 0) : (item.user_count ?? 0);
                        break;
                    case 'professor':
                        count = item.professor_count ?? 0;
                        break;
                    case 'employee':
                        count = item.employee_count ?? 0;
                        break;
                }
                return parseInt(count).toLocaleString();
            },

            getMemberSortLabel() {
                switch (this.memberSortType) {
                    case 'member':
                        return 'จำนวนนักศึกษา (คน)';
                    case 'professor':
                        return 'จำนวนอาจารย์ (คน)';
                    case 'employee':
                        return 'บุคลากร (คน)';
                }
                return 'จำนวน (คน)';
            }

        }))
    })
</script>