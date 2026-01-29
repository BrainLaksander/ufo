import {
  Building2,
  FileText,
  Clock,
  ClipboardList,
  TrendingUp,
  Calendar,
} from 'lucide-react';
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
} from 'recharts';

export default function Dashboard() {
  const stats = [
    {
      label: 'Total Organisasi Aktif',
      value: '24',
      icon: Building2,
      color: 'bg-[#3B1C57]',
      iconColor: 'text-white',
    },
    {
      label: 'Total Kegiatan Berjalan',
      value: '12',
      icon: FileText,
      color: 'bg-[#6B4C8A]',
      iconColor: 'text-white',
    },
    {
      label: 'Total Kegiatan Menunggu Persetujuan',
      value: '8',
      icon: Clock,
      color: 'bg-[#FFCC00]',
      iconColor: 'text-[#3B1C57]',
    },
    {
      label: 'Total Laporan Belum Direview',
      value: '5',
      icon: ClipboardList,
      color: 'bg-[#3B1C57]',
      iconColor: 'text-white',
    },
  ];

  const monthlyActivities = [
    { bulan: 'Jan', kegiatan: 8 },
    { bulan: 'Feb', kegiatan: 12 },
    { bulan: 'Mar', kegiatan: 15 },
    { bulan: 'Apr', kegiatan: 10 },
    { bulan: 'Mei', kegiatan: 18 },
    { bulan: 'Jun', kegiatan: 14 },
    { bulan: 'Jul', kegiatan: 20 },
    { bulan: 'Ago', kegiatan: 16 },
    { bulan: 'Sep', kegiatan: 22 },
    { bulan: 'Okt', kegiatan: 19 },
    { bulan: 'Nov', kegiatan: 17 },
    { bulan: 'Des', kegiatan: 13 },
  ];

  const recentAnnouncements = [
    {
      id: 1,
      judul: 'Pendaftaran Kegiatan Semester Genap 2024/2025',
      tanggal: '15 Desember 2024',
      status: 'Terpublikasi',
    },
    {
      id: 2,
      judul: 'Perubahan Format Pengajuan Proposal Kegiatan',
      tanggal: '10 Desember 2024',
      status: 'Terpublikasi',
    },
    {
      id: 3,
      judul: 'Batas Waktu Pengumpulan LPJ Kegiatan November',
      tanggal: '5 Desember 2024',
      status: 'Terpublikasi',
    },
    {
      id: 4,
      judul: 'Sosialisasi Sistem Informasi Kemahasiswaan Baru',
      tanggal: '1 Desember 2024',
      status: 'Draft',
    },
  ];

  return (
    <div className="space-y-6">
      {/* Statistik Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {stats.map((stat, index) => {
          const Icon = stat.icon;
          return (
            <div
              key={index}
              className="bg-white rounded-lg p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow"
            >
              <div className="flex items-start justify-between">
                <div className="flex-1">
                  <p className="text-sm text-gray-600 mb-3">{stat.label}</p>
                  <p className="text-4xl text-[#3B1C57]">{stat.value}</p>
                </div>
                <div className={`${stat.color} p-3 rounded-lg`}>
                  <Icon size={24} className={stat.iconColor} />
                </div>
              </div>
            </div>
          );
        })}
      </div>

      {/* Grafik dan Quick Actions */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Grafik Kegiatan Bulanan */}
        <div className="lg:col-span-2 bg-white rounded-lg p-6 shadow-sm border border-gray-200">
          <div className="flex items-center gap-2 mb-6">
            <TrendingUp size={20} className="text-[#3B1C57]" />
            <h3 className="text-lg text-[#3B1C57]">
              Grafik Kegiatan Bulanan 2024
            </h3>
          </div>
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={monthlyActivities}>
              <CartesianGrid strokeDasharray="3 3" stroke="#F4F4F4" />
              <XAxis
                dataKey="bulan"
                stroke="#666"
                style={{ fontSize: '12px' }}
              />
              <YAxis stroke="#666" style={{ fontSize: '12px' }} />
              <Tooltip
                contentStyle={{
                  backgroundColor: '#fff',
                  border: '1px solid #e5e7eb',
                  borderRadius: '8px',
                }}
              />
              <Bar dataKey="kegiatan" fill="#3B1C57" radius={[8, 8, 0, 0]} />
            </BarChart>
          </ResponsiveContainer>
        </div>

        {/* Quick Actions */}
        <div className="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
          <h3 className="text-lg text-[#3B1C57] mb-4">Quick Actions</h3>
          <div className="space-y-3">
            <button className="w-full bg-[#3B1C57] text-white px-4 py-3 rounded-lg hover:bg-[#6B4C8A] transition-colors">
              Review Kegiatan
            </button>
            <button className="w-full bg-[#FFCC00] text-[#3B1C57] px-4 py-3 rounded-lg hover:bg-[#FFD633] transition-colors">
              Buat Pengumuman
            </button>
          </div>

          <div className="mt-6 pt-6 border-t border-gray-200">
            <div className="flex items-center gap-2 mb-4">
              <Calendar size={18} className="text-[#3B1C57]" />
              <p className="text-sm text-gray-600">Kegiatan Mendatang</p>
            </div>
            <div className="space-y-3">
              <div className="p-3 bg-[#F4F4F4] rounded-lg">
                <p className="text-sm text-[#3B1C57]">Seminar Nasional HMTI</p>
                <p className="text-xs text-gray-500 mt-1">20 Desember 2024</p>
              </div>
              <div className="p-3 bg-[#F4F4F4] rounded-lg">
                <p className="text-sm text-[#3B1C57]">Festival Seni UKM</p>
                <p className="text-xs text-gray-500 mt-1">22 Desember 2024</p>
              </div>
              <div className="p-3 bg-[#F4F4F4] rounded-lg">
                <p className="text-sm text-[#3B1C57]">Rapat Koordinasi BEM</p>
                <p className="text-xs text-gray-500 mt-1">25 Desember 2024</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Pengumuman Terbaru */}
      <div className="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
        <div className="flex items-center justify-between mb-6">
          <h3 className="text-lg text-[#3B1C57]">Pengumuman Terbaru</h3>
          <button className="text-sm text-[#3B1C57] hover:underline">
            Lihat Semua
          </button>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-[#F4F4F4]">
              <tr>
                <th className="text-left px-3 md:px-6 py-3 text-sm text-[#3B1C57]">
                  Judul Pengumuman
                </th>
                <th className="text-left px-3 md:px-6 py-3 text-sm text-[#3B1C57] hidden sm:table-cell">
                  Tanggal
                </th>
                <th className="text-left px-3 md:px-6 py-3 text-sm text-[#3B1C57]">
                  Status
                </th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200">
              {recentAnnouncements.map((announcement) => (
                <tr
                  key={announcement.id}
                  className="hover:bg-gray-50 transition-colors"
                >
                  <td className="px-3 md:px-6 py-4">
                    <p>{announcement.judul}</p>
                    <p className="text-xs text-gray-500 mt-1 sm:hidden">
                      ({announcement.tanggal})
                    </p>
                  </td>
                  <td className="px-3 md:px-6 py-4 text-sm text-gray-600 hidden sm:table-cell">
                    ({announcement.tanggal})
                  </td>
                  <td className="px-3 md:px-6 py-4">
                    <span
                      className={`inline-block px-3 py-1 rounded-full text-xs ${
                        announcement.status === 'Terpublikasi'
                          ? 'bg-green-100 text-green-700'
                          : 'bg-gray-100 text-gray-700'
                      }`}
                    >
                      {announcement.status}
                    </span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
