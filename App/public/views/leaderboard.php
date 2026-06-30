<div x-data="leader()" x-init=" $nextTick(() => { lucide.createIcons() })" class="min-h-screen bg-gray-50">
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-3">
                    <a href="/" class="h-full flex items-center justify-center">
                        <img src="assets/images/bru_gogreen_logo.png" alt="BRU Waste Bank"
                            class="h-[60%] hover:cursor-pointer">
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <button @click="scrollTo('about')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">รู้จักโครงการ</button>
                    <button @click="scrollTo('video-guide')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">วิดีโอแนะนำ</button>
                    <button @click="scrollTo('how-it-works')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">การทำงาน</button>
                    <!-- <button @click="scrollTo('news')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">ข่าวสารและกิจกรรม</button> -->
                    <button @click="scrollTo('rewards')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">ของรางวัล</button>
                    <button @click="scrollTo('leaderboard')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">กระดานผู้นำ</button>
                    <a href="/login"
                        class="bg-white border-2 border-green-600 text-green-600 hover:bg-green-50 hover:cursor-pointer hover:scale-105 active:scale-98 px-6 py-2 rounded-full font-semibold transition-all">
                        เข้าสู่ระบบ
                    </a>
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
            <button @click="scrollTo('video-guide')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">วิดีโอแนะนำ</button>
            <button @click="scrollTo('how-it-works')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">การทำงาน</button>
            <button @click="scrollTo('rewards')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">ของรางวัล</button>
            <button @click="scrollTo('leaderboard')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">อันดับคณะ</button>
            <div class="pt-2">
                <button onclick="window.location.href='/login'"
                    class="w-full bg-green-600 text-white px-4 py-3 rounded-xl font-semibold cursor-pointer hover:scale-105 active:scale-98 hover:bg-green-700 active:bg-green-700">เข้าสู่ระบบ</button>
            </div>
        </div>
    </nav>


    <section id="leaderboard" class="py-12 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
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

            <div class="rounded-full p-2 md:p-3 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="bg-gray-100 p-1.5 rounded-full inline-flex relative shadow-inner">
                    <button @click="setLeaderboardType('faculty')"
                        :class="leaderboardType === 'faculty' ? 'bg-white shadow text-emerald-700 font-medium' : 'text-gray-400 hover:text-emerald-700 font-medium'"
                        class="px-6 py-2 rounded-full text-sm transition-all whitespace-nowrap flex-1 text-center flex gap-2 items-center cursor-pointer">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                        ระดับคณะ
                    </button>
                    <button @click="setLeaderboardType('major')"
                        :class="leaderboardType === 'major' ? 'bg-white shadow text-emerald-700 font-medium' : 'text-gray-400 hover:text-emerald-700 font-medium'"
                        class="px-6 py-2 rounded-full text-sm transition-all whitespace-nowrap flex-1 text-center flex gap-2 items-center cursor-pointer">
                        <i data-lucide="school" class="w-4 h-4"></i>
                        ระดับสาขา
                    </button>
                    <button @click="setLeaderboardType('member')"
                        :class="leaderboardType === 'member' ? 'bg-white shadow text-emerald-700 font-medium' : 'text-gray-400 hover:text-emerald-700 font-medium'"
                        class="px-6 py-2 rounded-full text-sm transition-all whitespace-nowrap flex-1 text-center flex gap-2 items-center cursor-pointer">
                        <i data-lucide="users-round" class="w-4 h-4"></i>
                        ระดับบุคคล
                    </button>
                </div>

                <div class="bg-gray-100 p-1.5 rounded-full inline-flex relative shadow-inner">
                    <button @click="setSortType('point')"
                        :class="{'bg-white shadow text-sky-600': sortType === 'point', 'text-gray-400 hover:text-sky-600': sortType !== 'point'}"
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="award" class="w-4 h-4"></i> แต้มขยะ
                    </button>
                    <button @click="setSortType('social')"
                        :class="{'bg-white shadow text-red-600': sortType === 'social', 'text-gray-400 hover:text-red-700': sortType !== 'social'}"
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="users" class="w-4 h-4"></i> แต้มสังคม
                    </button>
                    <!-- <button @click="setSortType('weight')"
                        :class="{'bg-white shadow text-emerald-600': sortType === 'weight', 'text-gray-400 hover:text-emerald-700': sortType !== 'weight'}"
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="scale" class="w-4 h-4"></i> น้ำหนัก
                    </button> -->
                    <button @click="setSortType('carbon')"
                        :class="{'bg-white shadow text-emerald-600': sortType === 'carbon', 'text-gray-400 hover:text-emerald-600': sortType !== 'carbon'}"
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="leaf" class="w-4 h-4"></i> ลดคาร์บอน
                    </button>

                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-center items-end gap-6 mb-16 px-4 md:px-12 mt-25"
                x-show="activeLeaderboard.length >= 3">

                <div class="w-full md:w-1/3 order-2 md:order-1 relative group mt-16 md:mt-0">
                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center">
                        <div class="relative">
                            <div
                                class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-gray-800 flex items-center justify-center overflow-hidden">
                                <template x-if="leaderboardType === 'member'">
                                    <span class="text-4xl font-bold text-blue-300"
                                        x-text="getFirstThaiChar(activeLeaderboard[1]?.name) || ''"></span>
                                </template>
                                <div x-show="leaderboardType !== 'member'">
                                    <i data-lucide="building-2" class="w-10 h-10 text-blue-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-2xl shadow-lg border border-gray-100 pt-16 pb-8 px-6 text-center transition-transform group-hover:-translate-y-2">
                        <span
                            class="inline-block px-3 py-1 bg-gray-100 text-gray-400 text-[8px] font-bold rounded-full mb-4">อันดับที่
                            2</span>
                        <h3 class="font-bold text-gray-900 text-xl line-clamp-1"
                            x-text="activeLeaderboard[1]?.name || '...'"></h3>
                        <template x-if="leaderboardType === 'major'">
                            <span class="text-gray-700 text-sm mb-1 line-clamp-1"
                                x-text="activeLeaderboard[1]?.faculty_name || '...'">
                            </span>
                        </template>
                        <div class="text-2xl font-black"
                            :class="sortType === 'point' ? 'text-sky-500' : sortType === 'social' ? 'text-red-600' : 'text-green-600'"
                            :class="sortType === 'point' ? 'text-sky-500' : sortType === 'social' ? 'text-red-600' : 'text-green-600'"
                            x-text="getSortValue(activeLeaderboard[1])">
                        </div>
                        <div class="text-xs text-gray-400 font-medium uppercase tracking-wider" x-text="getSortLabel()">
                        </div>

                    </div>
                </div>

                <div class="w-full md:w-1/3 order-1 md:order-2 relative z-10 group mt-16 md:mt-0">
                    <div class="absolute -top-30 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center">
                        <i data-lucide="crown" class="w-8 h-8 text-yellow-500 mb-1 drop-shadow-md animate-bounce "></i>
                        <div class="relative">
                            <div
                                class="w-32 h-32 rounded-full border-4 border-yellow-400 shadow-xl bg-gray-900 flex items-center justify-center overflow-hidden ring-4 ring-white">
                                <template x-if="leaderboardType === 'member'">
                                    <span class="text-5xl font-bold text-yellow-400"
                                        x-text="getFirstThaiChar(activeLeaderboard[0]?.name) || '?'"></span>
                                </template>
                                <div x-show="leaderboardType !== 'member'">
                                    <i data-lucide="trophy" class="w-14 h-14 text-yellow-500"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div
                        class="bg-white rounded-t-3xl rounded-b-2xl border border-gray-100 shadow-xl pt-20 pb-10 px-6 text-center transform md:-translate-y-6 transition-transform group-hover:-translate-y-8 relative">
                        <span
                            class="inline-block px-3 py-1 bg-yellow-300/70 text-yellow-700 text-[8px] font-bold rounded-full mb-4">
                            อันดับที่ 1
                        </span>
                        <h3 class="font-bold text-gray-900 text-2xl line-clamp-1"
                            x-text="activeLeaderboard[0]?.name || '...'">
                        </h3>
                        <template x-if="leaderboardType === 'major'">
                            <span class="text-gray-700 text-sm mb-1 line-clamp-1"
                                x-text="activeLeaderboard[0]?.faculty_name || '...'">
                            </span>
                        </template>
                        <div class="text-3xl font-black"
                            :class="sortType === 'point' ? 'text-sky-500' : sortType === 'social' ? 'text-red-600' : 'text-green-600'"
                            x-text="getSortValue(activeLeaderboard[0])">
                        </div>
                        <div class="text-xs text-gray-400 font-medium uppercase tracking-wider" x-text="getSortLabel()">
                        </div>

                    </div>
                </div>

                <div class="w-full md:w-1/3 order-3 relative group mt-16 md:mt-0">
                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center">
                        <div class="relative">
                            <div
                                class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-gray-800 flex items-center justify-center overflow-hidden">
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
                        class="bg-white rounded-2xl shadow-lg border border-gray-100 pt-16 pb-8 px-6 text-center transition-transform group-hover:-translate-y-2">
                        <span
                            class="inline-block px-3 py-1 bg-red-800/80 text-white text-[8px] font-bold rounded-full mb-4">อันดับที่
                            3</span>

                        <h3 class="font-bold text-gray-900 text-lg line-clamp-1"
                            x-text="activeLeaderboard[2]?.name || '...'">
                        </h3>
                        <template x-if="leaderboardType === 'major'">
                            <span class="text-gray-700 text-sm mb-1 line-clamp-1"
                                x-text="activeLeaderboard[2]?.faculty_name || '...'">
                            </span>
                        </template>
                        <div class="text-2xl font-black"
                            :class="sortType === 'point' ? 'text-sky-500' : sortType === 'social' ? 'text-red-600' : 'text-green-600'"
                            x-text="getSortValue(activeLeaderboard[2])">
                        </div>
                        <div class="text-xs text-gray-400 font-medium uppercase tracking-wider" x-text="getSortLabel()">
                        </div>

                    </div>
                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-x-auto"
                x-show="activeLeaderboard.length > 3">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-gray-600 font-bold text-sm uppercase w-5 tracking-wider">อันดับ
                            </th>
                            <th class="px-6 py-4 text-gray-600 font-bold text-sm uppercase tracking-wider"
                                x-text="leaderboardType === 'faculty' ? 'คณะ' : (leaderboardType === 'major' ? 'สาขา' : 'ชื่อ-สกุล')">
                            </th>
                            <th class="px-6 py-4 text-gray-600 font-bold text-sm uppercase tracking-wider text-center"
                                x-text="getSortLabel()"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="(item, index) in activeLeaderboard.slice(3)" :key="item.rank">
                            <tr class="hover:bg-blue-50/50 transition-colors group">
                                <td class="px-6 py-4 text-center">
                                    <span class="text-gray-400 font-bold text-lg w-6 text-center font-mono"
                                        x-text="item.rank"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm md:text-base group-hover:text-blue-700 transition-colors"
                                            x-text="item.name"></h4>
                                        <template x-if="leaderboardType !== 'member'">
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                ปริมาณขยะ: <span x-text="item.weight"></span> | ลดคาร์บอน: <span
                                                    x-text="item.carbon"></span> CO₂e
                                            </p>
                                        </template>
                                        <template x-if="leaderboardType === 'member'">
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                คณะ: <span x-text="item.fname"></span> | สาขา: <span
                                                    x-text="item.mname"></span>
                                            </p>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-lg font-black"
                                        :class="sortType === 'point' ? 'text-sky-500' : sortType === 'social' ? 'text-red-600' : 'text-green-600'"
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

    <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-sm">
                    © <span x-text="new Date().getFullYear()"></span>
                    มหาวิทยาลัยราชภัฏบุรีรัมย์.<br />สงวนลิขสิทธิ์.
                </p>
            </div>
        </div>
    </footer>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('leader', () => ({
            isMenuOpen: false,
            leaderboardType: 'faculty',
            sortType: 'point',
            facultyLeaderboard: [],
            majorLeaderboard: [],
            memberLeaderboard: [],

            init() {
                this.fetchAllLeaderboards();
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
                    case 'weight':
                        return item.weight;
                    case 'carbon':
                        return item.carbon;
                    case 'social':
                        return item.social;
                    case 'point':
                    default:
                        return item.points;
                }
            },

            getSortLabel() {
                switch (this.sortType) {
                    case 'weight':
                        return 'น้ำหนักรวม';
                    case 'carbon':
                        return 'คาร์บอนที่ลดได้';
                    case 'social':
                        return 'แต้มสังคม';
                    default:
                        return 'แต้มสะสม';
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
                            points: `${parseInt(item.total_point).toLocaleString()} แต้ม`,
                            weight: `${parseFloat(item.total_weight).toLocaleString('en-US', { maximumFractionDigits: 2 })} กก.`,
                            carbon: `${parseFloat(item.total_co2e).toLocaleString('en-US', { maximumFractionDigits: 2 })} CO2e`
                            , social: `${parseInt(item.total_social_point).toLocaleString()} แต้ม`,
                        }));

                        // Major
                        this.majorLeaderboard = result.data.major.map((item, index) => ({
                            rank: index + 1,
                            name: item.major_name,
                            faculty_name: `คณะ ${item.faculty_name}`,
                            points: `${parseInt(item.total_point).toLocaleString()} แต้ม`,
                            weight: `${parseFloat(item.total_weight).toLocaleString('en-US', { maximumFractionDigits: 2 })} กก.`,
                            carbon: `${parseFloat(item.total_co2e).toLocaleString('en-US', { maximumFractionDigits: 2 })} CO2e`,
                            social: `${parseInt(item.total_social_point).toLocaleString()} แต้ม`,
                        }));

                        // Member
                        this.memberLeaderboard = result.data.member.map((item, index) => ({
                            rank: index + 1,
                            name: item.member_name || item.name || 'ไม่ระบุชื่อ',
                            points: `${parseInt(item.total_point).toLocaleString()} แต้ม`,
                            weight: `${parseFloat(item.total_weight).toLocaleString('en-US', { maximumFractionDigits: 2 })} กก.`,
                            carbon: `${parseFloat(item.total_co2).toLocaleString('en-US', { maximumFractionDigits: 2 })} CO2e`,
                            social: `${parseInt(item.member_social_point).toLocaleString()} แต้ม`,
                            fname: item.faculty_name || 'ไม่ระบุคณะ',
                            mname: item.major_name || 'ไม่ระบุสาขา'
                        }));

                        setTimeout(() => { if (window.lucide) lucide.createIcons(); }, 100);
                    }
                } catch (error) {
                    console.error('Error fetching leaderboard:', error);
                }
            },


        }))
    })
</script>