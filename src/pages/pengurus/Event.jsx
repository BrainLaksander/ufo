import React, { useState } from 'react';
import {
  Calendar,
  Plus,
  Edit2,
  Trash2,
  Eye,
  ChevronDown,
  X,
  Upload,
} from 'lucide-react';

/**
 * Event Management Page - Pengurus
 *
 * Halaman untuk mengelola event organisasi:
 * - List event dengan filter status
 * - Create, Edit, Delete event
 * - View detail peserta event
 */
export default function Event() {
  const [events, setEvents] = useState([
    {
      id: 1,
      name: 'Rapat Organisasi Bulanan',
      date: '2026-03-05',
      time: '15:00',
      location: 'Ruang Rapat Lantai 2',
      quota: 150,
      participants: 87,
      status: 'open',
      description:
        'Rapat bulanan untuk membahas program dan keputusan organisasi',
      banner: null,
    },
    {
      id: 2,
      name: 'Diskusi Publik: Tech & Career',
      date: '2026-03-12',
      time: '14:00',
      location: 'Aula Utama',
      quota: 200,
      participants: 156,
      status: 'open',
      description: 'Diskusi publik dengan pembicara dari perusahaan teknologi',
      banner: null,
    },
    {
      id: 3,
      name: 'Workshop Python untuk Pemula',
      date: '2026-03-18',
      time: '13:00',
      location: 'Lab Komputer B',
      quota: 50,
      participants: 0,
      status: 'draft',
      description: 'Workshop interaktif dasar-dasar programming Python',
      banner: null,
    },
  ]);

  const [showForm, setShowForm] = useState(false);
  const [showDetail, setShowDetail] = useState(false);
  const [selectedEvent, setSelectedEvent] = useState(null);
  const [editingId, setEditingId] = useState(null);
  const [statusFilter, setStatusFilter] = useState('all');
  const [formData, setFormData] = useState({
    name: '',
    date: '',
    time: '',
    location: '',
    quota: '',
    description: '',
    status: 'draft',
    banner: null,
  });

  const filteredEvents =
    statusFilter === 'all'
      ? events
      : events.filter((e) => e.status === statusFilter);

  const openFormCreate = () => {
    setFormData({
      name: '',
      date: '',
      time: '',
      location: '',
      quota: '',
      description: '',
      status: 'draft',
      banner: null,
    });
    setEditingId(null);
    setShowForm(true);
  };

  const openFormEdit = (event) => {
    setFormData({
      name: event.name,
      date: event.date,
      time: event.time,
      location: event.location,
      quota: event.quota.toString(),
      description: event.description,
      status: event.status,
      banner: event.banner,
    });
    setEditingId(event.id);
    setShowForm(true);
  };

  const handleSave = () => {
    if (
      !formData.name ||
      !formData.date ||
      !formData.time ||
      !formData.location ||
      !formData.quota
    ) {
      alert('Mohon isi semua field required');
      return;
    }

    if (editingId) {
      setEvents(
        events.map((e) =>
          e.id === editingId
            ? {
                ...e,
                ...formData,
                quota: parseInt(formData.quota),
              }
            : e
        )
      );
    } else {
      const newEvent = {
        id: Math.max(...events.map((e) => e.id), 0) + 1,
        ...formData,
        quota: parseInt(formData.quota),
        participants: 0,
      };
      setEvents([...events, newEvent]);
    }

    setShowForm(false);
  };

  const handleDelete = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus event ini?')) {
      setEvents(events.filter((e) => e.id !== id));
    }
  };

  const viewDetail = (event) => {
    setSelectedEvent(event);
    setShowDetail(true);
  };

  const closePublish = (id) => {
    if (confirm('Tutup pendaftaran untuk event ini?')) {
      setEvents(
        events.map((e) => (e.id === id ? { ...e, status: 'closed' } : e))
      );
    }
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-gray-900">Event Organisasi</h1>
          <p className="text-gray-600 mt-1">
            Kelola semua event organisasi Anda di sini
          </p>
        </div>
        <button
          onClick={openFormCreate}
          className="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold border-2 border-blue-700"
        >
          <Plus size={20} />
          Buat Event
        </button>
      </div>

      {/* Filter Tabs */}
      <div className="flex gap-2 border-b-2 border-gray-200 overflow-x-auto pb-0">
        {['all', 'open', 'closed', 'draft', 'cancelled'].map((status) => (
          <button
            key={status}
            onClick={() => setStatusFilter(status)}
            className={`px-4 py-3 font-semibold text-sm border-b-2 transition-colors whitespace-nowrap ${
              statusFilter === status
                ? 'border-blue-600 text-blue-600'
                : 'border-transparent text-gray-600 hover:text-gray-900'
            }`}
          >
            {status === 'all'
              ? 'Semua'
              : status.charAt(0).toUpperCase() + status.slice(1)}
            {status !== 'all' && filteredEvents.length > 0 && (
              <span className="ml-2 text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full">
                {events.filter((e) => e.status === status).length}
              </span>
            )}
          </button>
        ))}
      </div>

      {/* Events List */}
      <div className="space-y-4">
        {filteredEvents.length === 0 ? (
          <div className="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
            <Calendar className="mx-auto mb-3 text-gray-400" size={48} />
            <p className="text-gray-600 font-semibold">Tidak ada event</p>
            <p className="text-gray-500 text-sm mt-1">
              Buat event baru untuk mulai
            </p>
          </div>
        ) : (
          filteredEvents.map((event) => (
            <div
              key={event.id}
              className="bg-white p-6 rounded-2xl border-2 border-gray-200 hover:shadow-lg transition-shadow"
            >
              <div className="flex items-start justify-between mb-4">
                <div className="flex-1">
                  <h3 className="text-xl font-bold text-gray-900">
                    {event.name}
                  </h3>
                  <p className="text-gray-600 text-sm mt-1">
                    {event.description}
                  </p>
                </div>
                <span
                  className={`px-4 py-2 rounded-lg text-sm font-bold border-2 whitespace-nowrap ml-4 ${
                    event.status === 'open'
                      ? 'bg-green-100 text-green-700 border-green-300'
                      : event.status === 'closed'
                        ? 'bg-red-100 text-red-700 border-red-300'
                        : event.status === 'draft'
                          ? 'bg-yellow-100 text-yellow-700 border-yellow-300'
                          : 'bg-gray-100 text-gray-700 border-gray-300'
                  }`}
                >
                  {event.status === 'open'
                    ? 'Dibuka'
                    : event.status === 'closed'
                      ? 'Ditutup'
                      : event.status === 'draft'
                        ? 'Draft'
                        : 'Dibatalkan'}
                </span>
              </div>

              <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 pb-4 border-b-2 border-gray-200">
                <div>
                  <p className="text-xs text-gray-500 uppercase">Tanggal</p>
                  <p className="text-sm font-semibold text-gray-900">
                    {event.date}
                  </p>
                </div>
                <div>
                  <p className="text-xs text-gray-500 uppercase">Waktu</p>
                  <p className="text-sm font-semibold text-gray-900">
                    {event.time}
                  </p>
                </div>
                <div>
                  <p className="text-xs text-gray-500 uppercase">Lokasi</p>
                  <p className="text-sm font-semibold text-gray-900">
                    {event.location}
                  </p>
                </div>
                <div>
                  <p className="text-xs text-gray-500 uppercase">Peserta</p>
                  <p className="text-sm font-semibold text-gray-900">
                    {event.participants} / {event.quota}
                  </p>
                </div>
              </div>

              {/* Participant Progress */}
              <div className="mb-4">
                <div className="w-full bg-gray-200 rounded-full h-2">
                  <div
                    className="bg-blue-500 h-2 rounded-full transition-all"
                    style={{
                      width: `${(event.participants / event.quota) * 100}%`,
                    }}
                  />
                </div>
              </div>

              {/* Actions */}
              <div className="flex gap-2 flex-wrap">
                <button
                  onClick={() => viewDetail(event)}
                  className="flex items-center gap-2 px-4 py-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors font-semibold border-2 border-blue-300"
                >
                  <Eye size={16} />
                  Lihat Detail
                </button>
                <button
                  onClick={() => openFormEdit(event)}
                  className="flex items-center gap-2 px-4 py-2 text-amber-600 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors font-semibold border-2 border-amber-300"
                >
                  <Edit2 size={16} />
                  Edit
                </button>
                {event.status === 'open' && (
                  <button
                    onClick={() => closePublish(event.id)}
                    className="flex items-center gap-2 px-4 py-2 text-orange-600 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors font-semibold border-2 border-orange-300"
                  >
                    <ChevronDown size={16} />
                    Tutup Pendaftaran
                  </button>
                )}
                <button
                  onClick={() => handleDelete(event.id)}
                  className="flex items-center gap-2 px-4 py-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors font-semibold border-2 border-red-300"
                >
                  <Trash2 size={16} />
                  Hapus
                </button>
              </div>
            </div>
          ))
        )}
      </div>

      {/* Form Modal */}
      {showForm && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl border-2 border-gray-300 max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <div className="sticky top-0 bg-white border-b-2 border-gray-200 p-6 flex items-center justify-between">
              <h2 className="text-2xl font-bold text-gray-900">
                {editingId ? 'Edit Event' : 'Buat Event Baru'}
              </h2>
              <button
                onClick={() => setShowForm(false)}
                className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
              >
                <X size={24} />
              </button>
            </div>

            <div className="p-6 space-y-4">
              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Nama Event *
                </label>
                <input
                  type="text"
                  value={formData.name}
                  onChange={(e) =>
                    setFormData({ ...formData, name: e.target.value })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                  placeholder="Masukkan nama event"
                />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-bold text-gray-900 mb-2">
                    Tanggal *
                  </label>
                  <input
                    type="date"
                    value={formData.date}
                    onChange={(e) =>
                      setFormData({ ...formData, date: e.target.value })
                    }
                    className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                  />
                </div>
                <div>
                  <label className="block text-sm font-bold text-gray-900 mb-2">
                    Waktu *
                  </label>
                  <input
                    type="time"
                    value={formData.time}
                    onChange={(e) =>
                      setFormData({ ...formData, time: e.target.value })
                    }
                    className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                  />
                </div>
              </div>

              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Lokasi *
                </label>
                <input
                  type="text"
                  value={formData.location}
                  onChange={(e) =>
                    setFormData({ ...formData, location: e.target.value })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                  placeholder="Masukkan lokasi event"
                />
              </div>

              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Kuota Peserta *
                </label>
                <input
                  type="number"
                  value={formData.quota}
                  onChange={(e) =>
                    setFormData({ ...formData, quota: e.target.value })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                  placeholder="Masukkan kuota peserta"
                />
              </div>

              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Deskripsi
                </label>
                <textarea
                  value={formData.description}
                  onChange={(e) =>
                    setFormData({ ...formData, description: e.target.value })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                  placeholder="Deskripsi event (opsional)"
                  rows={4}
                />
              </div>

              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Upload Banner
                </label>
                <div className="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer">
                  <Upload className="mx-auto mb-2 text-gray-400" size={28} />
                  <p className="text-gray-600">
                    Klik untuk upload atau drag & drop
                  </p>
                  <p className="text-xs text-gray-500 mt-1">
                    PNG, JPG, GIF (max 5MB)
                  </p>
                </div>
              </div>

              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Status
                </label>
                <select
                  value={formData.status}
                  onChange={(e) =>
                    setFormData({ ...formData, status: e.target.value })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                >
                  <option value="draft">Draft (Belum Dipublikasikan)</option>
                  <option value="open">Dibuka untuk Pendaftaran</option>
                </select>
              </div>

              <div className="flex gap-3 pt-4 border-t-2 border-gray-200">
                <button
                  onClick={handleSave}
                  className="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold border-2 border-blue-700"
                >
                  {editingId ? 'Simpan Perubahan' : 'Buat Event'}
                </button>
                <button
                  onClick={() => setShowForm(false)}
                  className="flex-1 px-6 py-3 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition-colors font-semibold border-2 border-gray-300"
                >
                  Batal
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Detail Modal */}
      {showDetail && selectedEvent && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl border-2 border-gray-300 max-w-2xl w-full shadow-2xl">
            <div className="border-b-2 border-gray-200 p-6 flex items-center justify-between">
              <h2 className="text-2xl font-bold text-gray-900">Detail Event</h2>
              <button
                onClick={() => setShowDetail(false)}
                className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
              >
                <X size={24} />
              </button>
            </div>

            <div className="p-6 space-y-4">
              <div className="bg-gray-50 p-4 rounded-lg border-2 border-gray-200">
                <h3 className="text-lg font-bold mb-4">{selectedEvent.name}</h3>

                <div className="grid grid-cols-2 gap-4 mb-6">
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Tanggal</p>
                    <p className="font-semibold text-gray-900">
                      {selectedEvent.date}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Waktu</p>
                    <p className="font-semibold text-gray-900">
                      {selectedEvent.time}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Lokasi</p>
                    <p className="font-semibold text-gray-900">
                      {selectedEvent.location}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Peserta</p>
                    <p className="font-semibold text-gray-900">
                      {selectedEvent.participants} / {selectedEvent.quota}
                    </p>
                  </div>
                </div>

                <div>
                  <p className="text-xs text-gray-500 uppercase mb-2">
                    Deskripsi
                  </p>
                  <p className="text-gray-900">{selectedEvent.description}</p>
                </div>
              </div>

              {/* Dummy participants */}
              <div>
                <h4 className="font-bold text-gray-900 mb-3">
                  Daftar Peserta ({selectedEvent.participants})
                </h4>
                <div className="space-y-2 max-h-64 overflow-y-auto">
                  {Array.from({
                    length: Math.min(5, selectedEvent.participants),
                  }).map((_, i) => (
                    <div
                      key={i}
                      className="flex items-center justify-between p-3 bg-gray-50 rounded-lg border-2 border-gray-200"
                    >
                      <div>
                        <p className="font-semibold text-gray-900">
                          Peserta #{i + 1}
                        </p>
                        <p className="text-xs text-gray-600">2024001</p>
                      </div>
                      <span className="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold border-2 border-green-300">
                        Hadir
                      </span>
                    </div>
                  ))}
                </div>
              </div>

              <div className="flex gap-3 pt-4 border-t-2 border-gray-200">
                <button
                  onClick={() => setShowDetail(false)}
                  className="flex-1 px-6 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-semibold border-2 border-blue-300"
                >
                  Tutup
                </button>
                <button className="flex-1 px-6 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors font-semibold border-2 border-green-300">
                  Export Peserta
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
