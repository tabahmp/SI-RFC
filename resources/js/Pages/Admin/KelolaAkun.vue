<template>
  <AppLayout>
    <div class="max-w-[1440px] mx-auto space-y-6">

      <!-- Header -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <div class="text-[11px] font-bold text-[#0757A8] tracking-wider uppercase mb-1">Administrator</div>
          <h1 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Kelola Akun Pengguna</h1>
          <p class="text-xs text-[#64748B] mt-0.5">Tambah akun baru, perbarui data, hapus, serta kelola aktivasi pengguna sistem.</p>
        </div>
        <div class="flex items-center gap-3">
          <!-- Badge tertunda -->
          <div v-if="akunTertunda > 0" class="flex items-center gap-2 px-3.5 py-2 bg-amber-50 border border-amber-200 rounded-xl">
            <Icon icon="lucide:alert-triangle" class="w-4 h-4 text-amber-500" />
            <span class="text-xs font-bold text-amber-700">{{ akunTertunda }} akun menunggu aktivasi</span>
          </div>

          <!-- Tombol Tambah Akun -->
          <button
            @click="bukaModalTambah"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0757A8] text-white text-xs font-bold rounded-xl hover:bg-[#063D78] transition-all shadow-sm active:scale-95"
          >
            <Icon icon="lucide:user-plus" class="w-4 h-4" />
            Tambah Akun
          </button>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="bg-white rounded-2xl border border-slate-200 p-4 flex flex-wrap gap-3 items-end shadow-xs">
        <div>
          <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1">Peran</label>
          <select v-model="filter.peran" class="text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white">
            <option value="">Semua Peran</option>
            <option value="pemohon">Pemohon</option>
            <option value="verifikator">Verifikator</option>
            <option value="pimpinan">Pimpinan</option>
            <option value="teknisi">Teknisi</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1">Status</label>
          <select v-model="filter.status" class="text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white">
            <option value="">Semua Status</option>
            <option value="tertunda">Tertunda</option>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
          </select>
        </div>
        <div class="flex-1 min-w-[200px]">
          <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1">Cari</label>
          <div class="relative">
            <Icon icon="lucide:search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" />
            <input
              v-model="filter.search"
              type="text"
              placeholder="Nama, email, atau NIP..."
              class="w-full pl-8 pr-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20"
            />
          </div>
        </div>
        <button @click="terapkanFilter" class="px-4 py-2 bg-[#0757A8] text-white text-xs font-bold rounded-lg hover:bg-[#063D78] transition-colors">
          Terapkan
        </button>
        <button @click="resetFilter" class="px-4 py-2 border border-slate-200 text-xs font-semibold text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
          Reset
        </button>
      </div>

      <!-- Tabel -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-xs">
            <thead class="bg-slate-50 border-b border-slate-100">
              <tr>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Pengguna</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">NIP & Unit Kerja</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">OPD</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Peran</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Status</th>
                <th class="text-left px-5 py-3 text-slate-500 font-semibold">Terdaftar</th>
                <th class="text-center px-5 py-3 text-slate-500 font-semibold">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-if="!pengguna?.data?.length">
                <td colspan="7" class="text-center py-12 text-slate-400">
                  <Icon icon="lucide:users" class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                  <p>Tidak ada pengguna ditemukan</p>
                </td>
              </tr>
              <tr v-for="user in pengguna?.data" :key="user.id" class="hover:bg-slate-50 transition-colors">
                <!-- Kolom Pengguna -->
                <td class="px-5 py-3">
                  <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-slate-100 text-[#0757A8] font-bold text-[11px] flex items-center justify-center shrink-0 border border-slate-200">
                      {{ getInisial(user.nama_lengkap) }}
                    </div>
                    <div>
                      <div class="font-semibold text-slate-800 flex items-center gap-1.5">
                        <span>{{ user.nama_lengkap }}</span>
                        <span v-if="user.id === authUserId" class="px-1.5 py-0.2 text-[9px] font-bold bg-blue-100 text-[#0757A8] rounded">Anda</span>
                      </div>
                      <div class="text-slate-400 text-[11px]">{{ user.email }}</div>
                      <div v-if="user.nomor_kontak" class="text-slate-400 text-[10px]">{{ user.nomor_kontak }}</div>
                    </div>
                  </div>
                </td>

                <!-- Kolom NIP & Unit Kerja -->
                <td class="px-5 py-3 text-slate-600">
                  <div class="font-medium">{{ user.nip || '-' }}</div>
                  <div v-if="user.unit_kerja" class="text-[11px] text-slate-400 line-clamp-1">{{ user.unit_kerja }}</div>
                </td>

                <!-- Kolom OPD -->
                <td class="px-5 py-3 text-slate-600 max-w-[180px]">
                  <span class="line-clamp-2">{{ user.opd }}</span>
                </td>

                <!-- Kolom Peran -->
                <td class="px-5 py-3">
                  <span
                    :class="[
                      'px-2.5 py-1 rounded-lg text-[10px] font-bold capitalize inline-flex items-center gap-1',
                      user.peran === 'admin'       ? 'bg-purple-50 text-purple-700 border border-purple-200' :
                      user.peran === 'pimpinan'    ? 'bg-amber-50 text-amber-700 border border-amber-200' :
                      user.peran === 'verifikator' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' :
                      user.peran === 'teknisi'     ? 'bg-teal-50 text-teal-700 border border-teal-200' :
                                                     'bg-blue-50 text-blue-700 border border-blue-200'
                    ]"
                  >
                    {{ user.peran }}
                  </span>
                </td>

                <!-- Kolom Status -->
                <td class="px-5 py-3">
                  <span
                    :class="[
                      'px-2.5 py-1 rounded-lg font-semibold capitalize inline-flex items-center gap-1 text-[11px]',
                      user.status_aktivasi === 'aktif'    ? 'bg-green-50 text-green-700 border border-green-200' :
                      user.status_aktivasi === 'tertunda' ? 'bg-amber-50 text-amber-700 border border-amber-200' :
                                                            'bg-red-50 text-red-700 border border-red-200'
                    ]"
                  >
                    <span
                      class="w-1.5 h-1.5 rounded-full"
                      :class="user.status_aktivasi === 'aktif' ? 'bg-green-500' : user.status_aktivasi === 'tertunda' ? 'bg-amber-500' : 'bg-red-500'"
                    ></span>
                    {{ user.status_aktivasi }}
                  </span>
                </td>

                <!-- Kolom Terdaftar -->
                <td class="px-5 py-3 text-slate-400 whitespace-nowrap">{{ user.created_at }}</td>

                <!-- Kolom Aksi -->
                <td class="px-5 py-3">
                  <div class="flex items-center justify-center gap-1">
                    <!-- Edit -->
                    <button
                      @click="bukaModalEdit(user)"
                      title="Edit Akun"
                      class="p-1.5 text-slate-600 hover:text-[#0757A8] hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-200"
                    >
                      <Icon icon="lucide:pencil" class="w-3.5 h-3.5" />
                    </button>

                    <!-- Aktifkan (jika bukan aktif) -->
                    <button
                      v-if="user.status_aktivasi !== 'aktif'"
                      @click="bukaKonfirmasi('aktivasi', user)"
                      title="Aktifkan Akun"
                      class="px-2 py-1 bg-green-600 text-white text-[10px] font-bold rounded-lg hover:bg-green-700 transition-colors flex items-center gap-1 shadow-xs"
                    >
                      <Icon icon="lucide:check" class="w-3 h-3" />
                      Aktifkan
                    </button>

                    <!-- Nonaktifkan (jika aktif) -->
                    <button
                      v-if="user.status_aktivasi === 'aktif'"
                      :disabled="user.id === authUserId"
                      @click="bukaKonfirmasi('nonaktif', user)"
                      :title="user.id === authUserId ? 'Tidak dapat menonaktifkan akun sendiri' : 'Nonaktifkan Akun'"
                      class="px-2 py-1 bg-slate-100 text-slate-700 text-[10px] font-bold rounded-lg hover:bg-slate-200 disabled:opacity-30 disabled:cursor-not-allowed transition-colors flex items-center gap-1 border border-slate-200"
                    >
                      <Icon icon="lucide:ban" class="w-3 h-3 text-slate-500" />
                      Nonaktif
                    </button>

                    <!-- Tolak (jika tertunda) -->
                    <button
                      v-if="user.status_aktivasi === 'tertunda'"
                      @click="bukaKonfirmasi('tolak', user)"
                      title="Tolak Pendaftaran"
                      class="px-2 py-1 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-lg hover:bg-amber-100 transition-colors flex items-center gap-1 border border-amber-200"
                    >
                      <Icon icon="lucide:x" class="w-3 h-3" />
                      Tolak
                    </button>

                    <!-- Hapus -->
                    <button
                      :disabled="user.id === authUserId"
                      @click="bukaKonfirmasi('hapus', user)"
                      :title="user.id === authUserId ? 'Tidak dapat menghapus akun sendiri' : 'Hapus Akun'"
                      class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-200 disabled:opacity-20 disabled:cursor-not-allowed"
                    >
                      <Icon icon="lucide:trash-2" class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="pengguna?.last_page > 1" class="px-5 py-4 border-t border-slate-100 flex items-center justify-between text-xs">
          <span class="text-slate-500">
            Menampilkan {{ pengguna.from }}–{{ pengguna.to }} dari {{ pengguna.total }} pengguna
          </span>
          <div class="flex gap-1">
            <a
              v-for="link in pengguna.links"
              :key="link.label"
              :href="link.url"
              v-html="link.label"
              :class="[
                'px-3 py-1.5 rounded-lg border text-xs font-semibold transition-colors',
                link.active ? 'bg-[#0757A8] text-white border-[#0757A8]' : 'border-slate-200 text-slate-600 hover:bg-slate-50',
                !link.url ? 'opacity-40 pointer-events-none' : ''
              ]"
            ></a>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Form Tambah / Edit Akun -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showModalForm"
          class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
          role="dialog"
          aria-modal="true"
        >
          <!-- Backdrop -->
          <div
            class="fixed inset-0 bg-black/50 backdrop-blur-sm"
            @click="tutupModalForm"
          ></div>

          <!-- Modal Box -->
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 z-10 animate-in zoom-in-95 duration-200 my-8">
            <!-- Header Modal -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0757A8] flex items-center justify-center shrink-0">
                  <Icon :icon="isEditing ? 'lucide:user-cog' : 'lucide:user-plus'" class="w-5 h-5" />
                </div>
                <div>
                  <h3 class="text-base font-bold text-[#0F172A]">
                    {{ isEditing ? 'Edit Akun Pengguna' : 'Tambah Akun Pengguna' }}
                  </h3>
                  <p class="text-xs text-[#64748B]">
                    {{ isEditing ? 'Perbarui informasi profil dan hak akses pengguna sistem' : 'Daftarkan pengguna baru dengan hak akses dan peran yang sesuai' }}
                  </p>
                </div>
              </div>
              <button
                @click="tutupModalForm"
                class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
              >
                <Icon icon="lucide:x" class="w-4 h-4" />
              </button>
            </div>

            <!-- Error Banner -->
            <div v-if="formErrorGeneral" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700 flex items-start gap-2">
              <Icon icon="lucide:alert-circle" class="w-4 h-4 shrink-0 mt-0.5 text-red-500" />
              <span>{{ formErrorGeneral }}</span>
            </div>

            <!-- Form Body -->
            <form @submit.prevent="simpanFormAkun" class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Nama Lengkap -->
                <div class="md:col-span-2">
                  <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="formAkun.nama_lengkap"
                    type="text"
                    required
                    placeholder="mis. Ahmad Fauzi, S.Kom"
                    class="w-full text-xs border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 transition-colors"
                    :class="formErrors.nama_lengkap ? 'border-red-400 bg-red-50/20' : 'border-slate-200'"
                  />
                  <p v-if="formErrors.nama_lengkap" class="mt-1 text-[11px] text-red-600">{{ formErrors.nama_lengkap[0] }}</p>
                </div>

                <!-- Email -->
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Email Akun / Dinas <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="formAkun.email"
                    type="email"
                    required
                    placeholder="nama@diskominfo.jabarprov.go.id"
                    class="w-full text-xs border rounded-lg px-3 py-2.5 focus:outline-none transition-colors"
                    :class="(formErrors.email || emailDomainError) ? 'border-red-400 bg-red-50/20 focus:ring-2 focus:ring-red-500/20' : 'border-slate-200 focus:ring-2 focus:ring-[#0757A8]/20'"
                  />
                  <div v-if="emailDomainError" class="mt-1 flex items-start gap-1 text-[10px] text-rose-600 font-medium leading-tight">
                    <Icon icon="lucide:alert-circle" class="w-3.5 h-3.5 flex-shrink-0 mt-0.5 text-rose-500" />
                    <span>{{ emailDomainError }}</span>
                  </div>
                  <div v-else-if="formAkun.email && isEmailValid" class="mt-1 flex items-center gap-1 text-[10px] text-emerald-600 font-medium leading-tight">
                    <Icon icon="lucide:check-circle-2" class="w-3.5 h-3.5 flex-shrink-0 text-emerald-500" />
                    <span>Email dinas resmi Pemerintah Provinsi Jawa Barat</span>
                  </div>
                  <p v-if="formErrors.email" class="mt-1 text-[11px] text-red-600">{{ formErrors.email[0] }}</p>
                </div>

                <!-- Peran -->
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Peran Pengguna <span class="text-red-500">*</span>
                  </label>
                  <select
                    v-model="formAkun.peran_id"
                    required
                    :disabled="isEditing && formAkun.id === authUserId"
                    class="w-full text-xs border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white transition-colors disabled:bg-slate-100 disabled:cursor-not-allowed"
                    :class="formErrors.peran_id ? 'border-red-400 bg-red-50/20' : 'border-slate-200'"
                  >
                    <option value="" disabled>-- Pilih Peran --</option>
                    <option v-for="p in daftarPeran" :key="p.id" :value="p.id">
                      {{ formatNamaPeran(p.nama_peran) }}
                    </option>
                  </select>
                  <p v-if="formErrors.peran_id" class="mt-1 text-[11px] text-red-600">{{ formErrors.peran_id[0] }}</p>
                </div>

                <!-- OPD Asal -->
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Perangkat Daerah (OPD)
                  </label>
                  <select
                    v-model="formAkun.opd_id"
                    @change="onSelectOpd"
                    class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white transition-colors"
                  >
                    <option value="">-- Tanpa OPD / Internal --</option>
                    <option v-for="o in daftarOpd" :key="o.id" :value="o.id">
                      {{ o.kode_opd }} - {{ o.nama_opd }}
                    </option>
                  </select>
                  <p v-if="formErrors.opd_id" class="mt-1 text-[11px] text-red-600">{{ formErrors.opd_id[0] }}</p>
                </div>

                <!-- Unit Kerja / Bidang -->
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Unit Kerja / Bidang
                  </label>
                  <input
                    v-model="formAkun.unit_kerja"
                    type="text"
                    placeholder="mis. Bidang Aplikasi Informatika"
                    class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 transition-colors"
                  />
                  <p v-if="formErrors.unit_kerja" class="mt-1 text-[11px] text-red-600">{{ formErrors.unit_kerja[0] }}</p>
                </div>

                <!-- NIP -->
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">
                    NIP (Nomor Induk Pegawai)
                  </label>
                  <input
                    v-model="formAkun.nip"
                    type="text"
                    placeholder="mis. 198904122014031002"
                    class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 transition-colors"
                  />
                  <p v-if="formErrors.nip" class="mt-1 text-[11px] text-red-600">{{ formErrors.nip[0] }}</p>
                </div>

                <!-- Nomor Kontak -->
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Nomor WhatsApp / Kontak
                  </label>
                  <input
                    v-model="formAkun.nomor_kontak"
                    type="tel"
                    placeholder="mis. 08123456789"
                    class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 transition-colors"
                  />
                  <p v-if="formErrors.nomor_kontak" class="mt-1 text-[11px] text-red-600">{{ formErrors.nomor_kontak[0] }}</p>
                </div>

                <!-- Status Akun -->
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Status Aktivasi <span class="text-red-500">*</span>
                  </label>
                  <select
                    v-model="formAkun.status_aktivasi"
                    required
                    :disabled="isEditing && formAkun.id === authUserId"
                    class="w-full text-xs border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 bg-white transition-colors disabled:bg-slate-100 disabled:cursor-not-allowed"
                    :class="formErrors.status_aktivasi ? 'border-red-400 bg-red-50/20' : 'border-slate-200'"
                  >
                    <option value="aktif">Aktif (Dapat langsung masuk)</option>
                    <option value="tertunda">Tertunda (Menunggu verifikasi)</option>
                    <option value="nonaktif">Nonaktif (Akses ditangguhkan)</option>
                  </select>
                  <p v-if="formErrors.status_aktivasi" class="mt-1 text-[11px] text-red-600">{{ formErrors.status_aktivasi[0] }}</p>
                </div>

                <!-- Kata Sandi -->
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Kata Sandi
                    <span v-if="!isEditing" class="text-red-500">*</span>
                    <span v-else class="text-slate-400 text-[10px] font-normal">(Kosongkan jika tidak diubah)</span>
                  </label>
                  <div class="relative">
                    <input
                      v-model="formAkun.password"
                      :type="showPassword ? 'text' : 'password'"
                      :required="!isEditing"
                      placeholder="Minimal 8 karakter unik"
                      class="w-full text-xs border rounded-lg pl-3 pr-9 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0757A8]/20 transition-colors"
                      :class="formErrors.password ? 'border-red-400 bg-red-50/20' : 'border-slate-200'"
                    />
                    <button
                      type="button"
                      @click="showPassword = !showPassword"
                      class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                    >
                      <Icon :icon="showPassword ? 'lucide:eye-off' : 'lucide:eye'" class="w-4 h-4" />
                    </button>
                  </div>
                  <p v-if="formErrors.password" class="mt-1 text-[11px] text-red-600">{{ formErrors.password[0] }}</p>
                </div>

                <!-- Indikator Keamanan Password (Live Strength & Checklist Syarat) -->
                <div v-if="formAkun.password" class="md:col-span-2 p-3 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-2">
                  <div class="flex items-center justify-between text-[11px]">
                    <span class="font-bold text-[#475569]">Kekuatan kata sandi:</span>
                    <span :class="passwordStrength.colorClass" class="font-extrabold uppercase tracking-wide">
                      {{ passwordStrength.label }}
                    </span>
                  </div>

                  <!-- Bar Progres Kekuatan -->
                  <div class="w-full h-1.5 bg-[#E2E8F0] rounded-full overflow-hidden flex gap-1">
                    <div
                      v-for="i in 4"
                      :key="i"
                      class="flex-1 h-full rounded-full transition-all duration-300"
                      :class="i <= passwordStrength.score ? passwordStrength.barClass : 'bg-[#E2E8F0]'"
                    ></div>
                  </div>

                  <!-- Checklist 4 Kriteria Keamanan -->
                  <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 text-[10.5px]">
                    <div class="flex items-center gap-1.5" :class="passwordRules.hasMinLength ? 'text-emerald-600 font-medium' : 'text-slate-400'">
                      <Icon :icon="passwordRules.hasMinLength ? 'lucide:check-circle-2' : 'lucide:circle'" class="w-3.5 h-3.5 flex-shrink-0" />
                      <span>Minimal 8 karakter</span>
                    </div>
                    <div class="flex items-center gap-1.5" :class="passwordRules.hasUpper ? 'text-emerald-600 font-medium' : 'text-slate-400'">
                      <Icon :icon="passwordRules.hasUpper ? 'lucide:check-circle-2' : 'lucide:circle'" class="w-3.5 h-3.5 flex-shrink-0" />
                      <span>Huruf kapital (A-Z)</span>
                    </div>
                    <div class="flex items-center gap-1.5" :class="passwordRules.hasNumber ? 'text-emerald-600 font-medium' : 'text-slate-400'">
                      <Icon :icon="passwordRules.hasNumber ? 'lucide:check-circle-2' : 'lucide:circle'" class="w-3.5 h-3.5 flex-shrink-0" />
                      <span>Angka (0-9)</span>
                    </div>
                    <div class="flex items-center gap-1.5" :class="passwordRules.hasSymbol ? 'text-emerald-600 font-medium' : 'text-slate-400'">
                      <Icon :icon="passwordRules.hasSymbol ? 'lucide:check-circle-2' : 'lucide:circle'" class="w-3.5 h-3.5 flex-shrink-0" />
                      <span>Simbol unik (@$!%*?&#)</span>
                    </div>
                  </div>
                </div>

              </div>

              <!-- Footer Modal -->
              <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 mt-6">
                <button
                  type="button"
                  @click="tutupModalForm"
                  class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  :disabled="formSaving || Boolean(emailDomainError) || !isEmailValid || !isPasswordValid"
                  class="px-6 py-2.5 bg-[#0757A8] text-white text-xs font-bold rounded-xl hover:bg-[#063D78] transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 shadow-sm"
                >
                  <Icon v-if="formSaving" icon="lucide:loader-2" class="w-3.5 h-3.5 animate-spin" />
                  <span>{{ formSaving ? 'Menyimpan...' : (isEditing ? 'Perbarui Akun' : 'Simpan Akun') }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Modal Konfirmasi Tindakan -->
    <KonfirmasiModal
      :show="modal.show"
      :judul="modal.judul"
      :pesan="modal.pesan"
      :label-konfirmasi="modal.labelKonfirmasi"
      :tipe-konfirmasi="modal.tipe"
      @konfirmasi="prosesAksi"
      @batal="modal.show = false"
    />

    <!-- Toast Feedback -->
    <Teleport to="body">
      <Transition name="toast-fade">
        <div
          v-if="toast.show"
          class="fixed bottom-6 right-6 z-50 flex items-center gap-2.5 px-4 py-3 bg-[#0F172A] text-white text-xs font-medium rounded-xl shadow-xl border border-slate-700/50 animate-in slide-in-from-bottom-2 duration-200"
        >
          <Icon icon="lucide:check-circle-2" class="w-4 h-4 text-green-400 shrink-0" />
          <span>{{ toast.pesan }}</span>
        </div>
      </Transition>
    </Teleport>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router }        from '@inertiajs/vue3'
import AppLayout         from '@/Layouts/AppLayout.vue'
import KonfirmasiModal   from '@/Components/KonfirmasiModal.vue'
import axios             from 'axios'

const props = defineProps({
  pengguna:    { type: Object, default: () => ({ data: [], last_page: 1 }) },
  filterAktif: { type: Object, default: () => ({}) },
  daftarPeran: { type: Array,  default: () => [] },
  daftarOpd:   { type: Array,  default: () => [] },
  authUserId:  { type: [Number, String], default: null },
})

const filter = ref({
  peran:  props.filterAktif.peran   ?? '',
  status: props.filterAktif.status  ?? '',
  search: props.filterAktif.search  ?? '',
})

const akunTertunda = computed(() =>
  props.pengguna?.data?.filter(u => u.status_aktivasi === 'tertunda').length ?? 0
)

function getInisial(nama) {
  if (!nama) return 'U'
  const parts = nama.trim().split(/\s+/)
  if (parts.length >= 2) {
    return (parts[0][0] + parts[1][0]).toUpperCase()
  }
  return nama.slice(0, 2).toUpperCase()
}

function formatNamaPeran(namaPeran) {
  if (!namaPeran) return ''
  return namaPeran.charAt(0).toUpperCase() + namaPeran.slice(1)
}

// ── Filter ───────────────────────────────────────────────────────────────────
function terapkanFilter() {
  router.get('/admin/akun', filter.value, { preserveState: true, replace: true })
}
function resetFilter() {
  filter.value = { peran: '', status: '', search: '' }
  router.get('/admin/akun', {}, { preserveState: true, replace: true })
}

// ── Toast ────────────────────────────────────────────────────────────────────
const toast = ref({ show: false, pesan: '' })
let toastTimer = null
function tampilToast(pesan) {
  clearTimeout(toastTimer)
  toast.value = { show: true, pesan }
  toastTimer = setTimeout(() => {
    toast.value.show = false
  }, 3500)
}

// ── Modal Form Tambah / Edit ─────────────────────────────────────────────────
const showModalForm   = ref(false)
const isEditing       = ref(false)
const formSaving      = ref(false)
const showPassword    = ref(false)
const formErrors      = ref({})
const formErrorGeneral= ref('')

const formAkun = ref({
  id: null,
  nama_lengkap: '',
  email: '',
  nip: '',
  nomor_kontak: '',
  unit_kerja: '',
  peran_id: '',
  opd_id: '',
  status_aktivasi: 'aktif',
  password: '',
})

const emailRegexJabar = /^[a-zA-Z0-9._%+-]+@([a-zA-Z0-9.-]+\.)?jabarprov\.go\.id$/i

const emailDomainError = computed(() => {
  const email = (formAkun.value.email || '').trim()
  if (!email) return null

  if (!email.includes('@')) return null

  const domainPart = email.split('@')[1] || ''
  if (!domainPart) {
    return 'Lengkapi domain email dinas (@*.jabarprov.go.id atau @jabarprov.go.id).'
  }

  if (!emailRegexJabar.test(email)) {
    return 'Hanya email dinas resmi Pemerintah Provinsi Jawa Barat (@*.jabarprov.go.id atau @jabarprov.go.id) yang dapat didaftarkan.'
  }

  return null
})

const isEmailValid = computed(() => {
  const email = (formAkun.value.email || '').trim()
  return email.length > 0 && emailRegexJabar.test(email)
})

// Evaluasi aturan keamanan password (Kapital, Simbol, Angka, Minimal 8 Karakter)
const passwordRules = computed(() => {
  const pwd = formAkun.value.password || ''
  return {
    hasMinLength: pwd.length >= 8,
    hasUpper: /[A-Z]/.test(pwd),
    hasNumber: /[0-9]/.test(pwd),
    hasSymbol: /[^A-Za-z0-9]/.test(pwd),
  }
})

const passwordStrength = computed(() => {
  const rules = passwordRules.value
  let score = 0
  if (rules.hasMinLength) score++
  if (rules.hasUpper) score++
  if (rules.hasNumber) score++
  if (rules.hasSymbol) score++

  if (score <= 1) {
    return { score, label: 'Sangat Lemah', colorClass: 'text-rose-500', barClass: 'bg-rose-500' }
  } else if (score === 2) {
    return { score, label: 'Lemah', colorClass: 'text-amber-500', barClass: 'bg-amber-500' }
  } else if (score === 3) {
    return { score, label: 'Sedang', colorClass: 'text-sky-600', barClass: 'bg-sky-600' }
  } else {
    return { score, label: 'Kuat & Aman', colorClass: 'text-emerald-600', barClass: 'bg-emerald-600' }
  }
})

const isPasswordValid = computed(() => {
  const pwd = formAkun.value.password || ''
  if (isEditing.value && !pwd) return true
  const r = passwordRules.value
  return r.hasMinLength && r.hasUpper && r.hasNumber && r.hasSymbol
})

function onSelectOpd() {
  if (!formAkun.value.opd_id) {
    const currentEmail = (formAkun.value.email || '').trim()
    if (currentEmail.startsWith('@')) {
      formAkun.value.email = ''
    } else if (currentEmail.includes('@')) {
      const prefix = currentEmail.split('@')[0]
      formAkun.value.email = prefix ? `${prefix}@jabarprov.go.id` : ''
    }
    return
  }

  const selected = props.daftarOpd.find(o => o.id == formAkun.value.opd_id)
  if (!selected || !selected.kode_opd) return

  const domain = `@${selected.kode_opd.toLowerCase()}.jabarprov.go.id`
  const currentEmail = (formAkun.value.email || '').trim()

  if (!currentEmail || currentEmail.startsWith('@')) {
    formAkun.value.email = domain
  } else if (currentEmail.includes('@')) {
    const prefix = currentEmail.split('@')[0]
    formAkun.value.email = `${prefix}${domain}`
  } else {
    formAkun.value.email = `${currentEmail}${domain}`
  }
}

function bukaModalTambah() {
  isEditing.value = false
  showPassword.value = false
  formErrors.value = {}
  formErrorGeneral.value = ''
  
  // Default peran ke 'pemohon' jika ada
  const peranPemohon = props.daftarPeran.find(p => p.nama_peran === 'pemohon')
  formAkun.value = {
    id: null,
    nama_lengkap: '',
    email: '',
    nip: '',
    nomor_kontak: '',
    unit_kerja: '',
    peran_id: peranPemohon ? peranPemohon.id : (props.daftarPeran[0]?.id ?? ''),
    opd_id: '',
    status_aktivasi: 'aktif',
    password: '',
  }
  showModalForm.value = true
}

function bukaModalEdit(user) {
  isEditing.value = true
  showPassword.value = false
  formErrors.value = {}
  formErrorGeneral.value = ''

  // Ambil peran_id berdasarkan user.peran_id atau cari dari daftarPeran
  let peranId = user.peran_id
  if (!peranId && user.peran) {
    const match = props.daftarPeran.find(p => p.nama_peran.toLowerCase() === user.peran.toLowerCase())
    if (match) peranId = match.id
  }

  // Ambil opd_id berdasarkan user.opd_id atau cari dari daftarOpd
  let opdId = user.opd_id
  if (!opdId && user.opd && user.opd !== '-') {
    const match = props.daftarOpd.find(o => o.nama_opd === user.opd || o.kode_opd === user.opd)
    if (match) opdId = match.id
  }

  formAkun.value = {
    id: user.id,
    nama_lengkap: user.nama_lengkap || '',
    email: user.email || '',
    nip: (user.nip && user.nip !== '-') ? user.nip : '',
    nomor_kontak: (user.nomor_kontak && user.nomor_kontak !== '-') ? user.nomor_kontak : '',
    unit_kerja: user.unit_kerja || '',
    peran_id: peranId || (props.daftarPeran[0]?.id ?? ''),
    opd_id: opdId || '',
    status_aktivasi: user.status_aktivasi || 'aktif',
    password: '',
  }
  showModalForm.value = true
}

function tutupModalForm() {
  showModalForm.value = false
  formErrors.value = {}
  formErrorGeneral.value = ''
}

async function simpanFormAkun() {
  if (formSaving.value) return
  formErrors.value = {}
  formErrorGeneral.value = ''

  if (!isEmailValid.value) {
    formErrorGeneral.value = 'Hanya email dinas resmi Pemerintah Provinsi Jawa Barat (@*.jabarprov.go.id atau @jabarprov.go.id) yang dapat didaftarkan.'
    return
  }

  if (!isPasswordValid.value) {
    formErrorGeneral.value = 'Kata sandi harus minimal 8 karakter dan mengandung huruf kapital, angka, serta simbol unik.'
    return
  }

  formSaving.value = true

  try {
    if (isEditing.value) {
      const res = await axios.patch(`/admin/akun/${formAkun.value.id}`, formAkun.value)
      tutupModalForm()
      tampilToast(res.data?.message || 'Data akun berhasil diperbarui.')
    } else {
      const res = await axios.post('/admin/akun', formAkun.value)
      tutupModalForm()
      tampilToast(res.data?.message || 'Akun pengguna berhasil ditambahkan.')
    }
    router.reload({ preserveScroll: true })
  } catch (error) {
    if (error.response?.status === 422) {
      formErrors.value = error.response.data?.errors || {}
      formErrorGeneral.value = error.response.data?.message || 'Terdapat kesalahan pada input formulir.'
    } else {
      formErrorGeneral.value = error.response?.data?.message || 'Terjadi kesalahan sistem. Silakan coba lagi.'
    }
  } finally {
    formSaving.value = false
  }
}

// ── Modal Konfirmasi Tindakan ────────────────────────────────────────────────
const modal = ref({ show: false, tipe: 'danger', judul: '', pesan: '', labelKonfirmasi: '', aksi: '', user: null })

function bukaKonfirmasi(aksi, user) {
  const cfg = {
    aktivasi: {
      tipe: 'success',
      judul: 'Aktifkan Akun Pengguna',
      pesan: `Aktifkan akun ${user.nama_lengkap}? Pengguna akan dapat langsung masuk ke sistem.`,
      label: 'Ya, Aktifkan',
    },
    nonaktif: {
      tipe: 'warning',
      judul: 'Nonaktifkan Akun Pengguna',
      pesan: `Nonaktifkan akun ${user.nama_lengkap}? Pengguna tidak akan dapat masuk ke sistem hingga diaktifkan kembali.`,
      label: 'Ya, Nonaktifkan',
    },
    tolak: {
      tipe: 'danger',
      judul: 'Tolak Pendaftaran Pengguna',
      pesan: `Tolak pendaftaran akun ${user.nama_lengkap}? Status akun akan disetel menjadi nonaktif.`,
      label: 'Ya, Tolak',
    },
    hapus: {
      tipe: 'danger',
      judul: 'Hapus Akun Pengguna',
      pesan: `Apakah Anda yakin ingin menghapus akun ${user.nama_lengkap} (${user.email})? Data akun ini akan dihapus secara permanen.`,
      label: 'Ya, Hapus Akun',
    },
  }[aksi]

  modal.value = {
    show: true,
    aksi,
    user,
    tipe: cfg.tipe,
    judul: cfg.judul,
    pesan: cfg.pesan,
    labelKonfirmasi: cfg.label,
  }
}

const loadingAksi = ref(false)
async function prosesAksi() {
  if (loadingAksi.value) return
  loadingAksi.value = true
  const { aksi, user } = modal.value
  modal.value.show = false

  try {
    if (aksi === 'hapus') {
      const res = await axios.delete(`/admin/akun/${user.id}`)
      tampilToast(res.data?.message || 'Akun pengguna berhasil dihapus.')
    } else {
      const urlMap = {
        aktivasi: `/admin/akun/${user.id}/aktivasi`,
        nonaktif: `/admin/akun/${user.id}/nonaktif`,
        tolak:    `/admin/akun/${user.id}/tolak`,
      }
      const res = await axios.patch(urlMap[aksi])
      tampilToast(res.data?.message || 'Status akun berhasil diperbarui.')
    }
    router.reload({ preserveScroll: true })
  } catch (e) {
    alert(e.response?.data?.message || 'Terjadi kesalahan saat memproses aksi.')
  } finally {
    loadingAksi.value = false
  }
}
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter-from,
.modal-fade-leave-to    { opacity: 0; }

.toast-fade-enter-active,
.toast-fade-leave-active { transition: all 0.25s ease; }
.toast-fade-enter-from,
.toast-fade-leave-to    { opacity: 0; transform: translateY(10px); }
</style>