import React, { useState } from 'react';
import { Settings, Save, X, Upload, Edit2 } from 'lucide-react';

/**
 * Pengaturan Organisasi Page - Pengurus
 *
 * Halaman untuk mengatur informasi organisasi:
 * - Edit deskripsi & visi misi
 * - Edit kontak sosial media
 * - Upload logo & banner
 */
export default function Pengaturan() {
  const [orgData, setOrgData] = useState({
    name: 'UFO - University Student Organization Forum',
    description:
      'UFO adalah organisasi mahasiswa yang fokus pada pengembangan kemitraan antar organisasi di tingkat universitas.',
    vision:
      'Menjadi forum utama yang memfasilitasi kolaborasi dan pertumbuhan organisasi mahasiswa di universitas.',
    mission: [
      'Memfasilitasi jaringan dan kolaborasi antar organisasi mahasiswa',
      'Mengadakan forum diskusi dan sharing pengalaman organisasi',
      'Mendukung pengembangan kapasitas organisasi mahasiswa',
      'Menjadi jembatan komunikasi antara organisasi dan pihak universitas',
    ],
    contact: {
      email: 'ufo@universitas.ac.id',
      phone: '0812-3456-7890',
    },
    social: {
      instagram: 'https://instagram.com/ufo_univ',
      facebook: 'https://facebook.com/ufoorganisasi',
      tiktok: 'https://tiktok.com/@ufo_univ',
      linkedin: 'https://linkedin.com/company/ufo-univ',
    },
    logo: null,
    banner: null,
  });

  const [isEditingInfo, setIsEditingInfo] = useState(false);
  const [isEditingSocial, setIsEditingSocial] = useState(false);
  const [tempData, setTempData] = useState(JSON.parse(JSON.stringify(orgData)));
  const [successMessage, setSuccessMessage] = useState('');

  const showSuccess = (message) => {
    setSuccessMessage(message);
    setTimeout(() => setSuccessMessage(''), 3000);
  };

  const handleSaveInfo = () => {
    setOrgData({
      ...orgData,
      description: tempData.description,
      vision: tempData.vision,
      mission: tempData.mission,
    });
    setIsEditingInfo(false);
    showSuccess('Informasi organisasi berhasil diperbarui');
  };

  const handleSaveSocial = () => {
    setOrgData({
      ...orgData,
      contact: tempData.contact,
      social: tempData.social,
    });
    setIsEditingSocial(false);
    showSuccess('Kontak dan media sosial berhasil diperbarui');
  };

  const handleMissionChange = (index, value) => {
    const newMission = [...tempData.mission];
    newMission[index] = value;
    setTempData({ ...tempData, mission: newMission });
  };

  const addMission = () => {
    setTempData({
      ...tempData,
      mission: [...tempData.mission, ''],
    });
  };

  const removeMission = (index) => {
    setTempData({
      ...tempData,
      mission: tempData.mission.filter((_, i) => i !== index),
    });
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900">
          Pengaturan Organisasi
        </h1>
        <p className="text-gray-600 mt-1">
          Kelola informasi dan pengaturan organisasi Anda
        </p>
      </div>

      {/* Success Message */}
      {successMessage && (
        <div className="p-4 bg-green-100 text-green-700 rounded-xl border-2 border-green-300 flex items-center gap-2">
          <span>✓</span>
          <span>{successMessage}</span>
        </div>
      )}

      {/* Org Name (Read-only) */}
      <div className="bg-white p-6 rounded-2xl border-2 border-gray-200">
        <div className="flex items-center justify-between mb-4">
          <h2 className="text-xl font-bold text-gray-900">Nama Organisasi</h2>
          <span className="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold border-2 border-yellow-300">
            Tidak Dapat Diubah
          </span>
        </div>
        <p className="text-gray-900 text-lg font-semibold">{orgData.name}</p>
        <p className="text-gray-600 text-sm mt-2">
          Nama organisasi tidak dapat diubah. Hubungi admin universitas jika
          ingin mengubah nama.
        </p>
      </div>

      {/* Info & Vision/Mission */}
      <div className="bg-white p-6 rounded-2xl border-2 border-gray-200">
        <div className="flex items-center justify-between mb-6">
          <h2 className="text-xl font-bold text-gray-900">
            Deskripsi & Visi Misi
          </h2>
          {!isEditingInfo ? (
            <button
              onClick={() => {
                setTempData(JSON.parse(JSON.stringify(orgData)));
                setIsEditingInfo(true);
              }}
              className="flex items-center gap-2 px-4 py-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors font-semibold border-2 border-blue-300"
            >
              <Edit2 size={16} />
              Edit
            </button>
          ) : null}
        </div>

        {!isEditingInfo ? (
          <div className="space-y-6">
            <div>
              <p className="text-xs text-gray-500 uppercase font-bold mb-2">
                Deskripsi Organisasi
              </p>
              <p className="text-gray-900">{orgData.description}</p>
            </div>

            <div>
              <p className="text-xs text-gray-500 uppercase font-bold mb-2">
                Visi
              </p>
              <p className="text-gray-900">{orgData.vision}</p>
            </div>

            <div>
              <p className="text-xs text-gray-500 uppercase font-bold mb-2">
                Misi
              </p>
              <ul className="space-y-2">
                {orgData.mission.map((item, idx) => (
                  <li key={idx} className="flex gap-3 text-gray-900">
                    <span className="text-blue-600 font-bold">{idx + 1}.</span>
                    <span>{item}</span>
                  </li>
                ))}
              </ul>
            </div>
          </div>
        ) : (
          <div className="space-y-4">
            <div>
              <label className="block text-sm font-bold text-gray-900 mb-2">
                Deskripsi Organisasi
              </label>
              <textarea
                value={tempData.description}
                onChange={(e) =>
                  setTempData({ ...tempData, description: e.target.value })
                }
                className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                rows={4}
              />
            </div>

            <div>
              <label className="block text-sm font-bold text-gray-900 mb-2">
                Visi
              </label>
              <textarea
                value={tempData.vision}
                onChange={(e) =>
                  setTempData({ ...tempData, vision: e.target.value })
                }
                className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                rows={3}
              />
            </div>

            <div>
              <label className="block text-sm font-bold text-gray-900 mb-2">
                Misi
              </label>
              <div className="space-y-2">
                {tempData.mission.map((item, idx) => (
                  <div key={idx} className="flex gap-2 items-start">
                    <span className="text-gray-600 font-bold mt-2">
                      {idx + 1}.
                    </span>
                    <div className="flex-1">
                      <textarea
                        value={item}
                        onChange={(e) =>
                          handleMissionChange(idx, e.target.value)
                        }
                        className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                        rows={2}
                      />
                    </div>
                    <button
                      onClick={() => removeMission(idx)}
                      className="px-3 py-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors border-2 border-red-300 mt-2"
                    >
                      <X size={16} />
                    </button>
                  </div>
                ))}
              </div>
              <button
                onClick={addMission}
                className="mt-3 px-4 py-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors font-semibold border-2 border-blue-300 text-sm"
              >
                + Tambah Misi
              </button>
            </div>

            <div className="flex gap-3 pt-4 border-t-2 border-gray-200">
              <button
                onClick={handleSaveInfo}
                className="flex-1 flex items-center justify-center gap-2 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold border-2 border-green-700"
              >
                <Save size={16} />
                Simpan
              </button>
              <button
                onClick={() => setIsEditingInfo(false)}
                className="flex-1 px-6 py-2 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition-colors font-semibold border-2 border-gray-300"
              >
                Batal
              </button>
            </div>
          </div>
        )}
      </div>

      {/* Contact & Social Media */}
      <div className="bg-white p-6 rounded-2xl border-2 border-gray-200">
        <div className="flex items-center justify-between mb-6">
          <h2 className="text-xl font-bold text-gray-900">
            Kontak & Media Sosial
          </h2>
          {!isEditingSocial ? (
            <button
              onClick={() => {
                setTempData(JSON.parse(JSON.stringify(orgData)));
                setIsEditingSocial(true);
              }}
              className="flex items-center gap-2 px-4 py-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors font-semibold border-2 border-blue-300"
            >
              <Edit2 size={16} />
              Edit
            </button>
          ) : null}
        </div>

        {!isEditingSocial ? (
          <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <p className="text-xs text-gray-500 uppercase font-bold mb-2">
                  Email
                </p>
                <p className="text-gray-900 break-all">
                  {orgData.contact.email}
                </p>
              </div>
              <div>
                <p className="text-xs text-gray-500 uppercase font-bold mb-2">
                  Nomor Telepon
                </p>
                <p className="text-gray-900">{orgData.contact.phone}</p>
              </div>
            </div>

            <div className="border-t-2 border-gray-200 pt-6">
              <p className="text-xs text-gray-500 uppercase font-bold mb-4">
                Media Sosial
              </p>
              <div className="space-y-3">
                {Object.entries(orgData.social).map(([key, value]) => (
                  <div key={key}>
                    <p className="text-sm font-semibold text-gray-900 capitalize mb-1">
                      {key}
                    </p>
                    <p className="text-blue-600 break-all text-sm">
                      {value || '-'}
                    </p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        ) : (
          <div className="space-y-4">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Email
                </label>
                <input
                  type="email"
                  value={tempData.contact.email}
                  onChange={(e) =>
                    setTempData({
                      ...tempData,
                      contact: { ...tempData.contact, email: e.target.value },
                    })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                />
              </div>
              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Nomor Telepon
                </label>
                <input
                  type="tel"
                  value={tempData.contact.phone}
                  onChange={(e) =>
                    setTempData({
                      ...tempData,
                      contact: { ...tempData.contact, phone: e.target.value },
                    })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                />
              </div>
            </div>

            <div className="space-y-3">
              {Object.entries(tempData.social).map(([key, value]) => (
                <div key={key}>
                  <label className="block text-sm font-bold text-gray-900 mb-2 capitalize">
                    {key}
                  </label>
                  <input
                    type="url"
                    value={value}
                    onChange={(e) =>
                      setTempData({
                        ...tempData,
                        social: { ...tempData.social, [key]: e.target.value },
                      })
                    }
                    className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                    placeholder={`https://...`}
                  />
                </div>
              ))}
            </div>

            <div className="flex gap-3 pt-4 border-t-2 border-gray-200">
              <button
                onClick={handleSaveSocial}
                className="flex-1 flex items-center justify-center gap-2 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold border-2 border-green-700"
              >
                <Save size={16} />
                Simpan
              </button>
              <button
                onClick={() => setIsEditingSocial(false)}
                className="flex-1 px-6 py-2 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition-colors font-semibold border-2 border-gray-300"
              >
                Batal
              </button>
            </div>
          </div>
        )}
      </div>

      {/* Logo & Banner */}
      <div className="bg-white p-6 rounded-2xl border-2 border-gray-200">
        <h2 className="text-xl font-bold text-gray-900 mb-6">
          Logo & Banner Organisasi
        </h2>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          {/* Logo */}
          <div>
            <p className="text-sm font-bold text-gray-900 mb-3">
              Logo Organisasi
            </p>
            <div className="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-colors cursor-pointer bg-gray-50">
              <Upload className="mx-auto mb-2 text-gray-400" size={32} />
              <p className="text-gray-600 text-sm font-semibold">
                Klik untuk upload
              </p>
              <p className="text-xs text-gray-500 mt-1">PNG, JPG (max 5MB)</p>
            </div>
          </div>

          {/* Banner */}
          <div>
            <p className="text-sm font-bold text-gray-900 mb-3">
              Banner Organisasi
            </p>
            <div className="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-colors cursor-pointer bg-gray-50">
              <Upload className="mx-auto mb-2 text-gray-400" size={32} />
              <p className="text-gray-600 text-sm font-semibold">
                Klik untuk upload
              </p>
              <p className="text-xs text-gray-500 mt-1">
                PNG, JPG, GIF (max 10MB)
              </p>
            </div>
          </div>
        </div>

        <p className="text-xs text-gray-500 mt-4">
          💡 Tips: Ukuran rekomendasi Banner: 1920x600px
        </p>
      </div>

      {/* Warning Section */}
      <div className="bg-red-50 p-6 rounded-2xl border-2 border-red-200">
        <h2 className="text-lg font-bold text-red-700 mb-3">⚠️ Zona Bahaya</h2>
        <p className="text-gray-900 mb-4">
          Tindakan berikut tidak dapat dibatalkan. Gunakan dengan hati-hati.
        </p>
        <button className="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold border-2 border-red-700 cursor-not-allowed opacity-50">
          Hapus Organisasi (Disabled)
        </button>
        <p className="text-xs text-gray-600 mt-2">
          Fitur ini hanya tersedia untuk admin universitas.
        </p>
      </div>
    </div>
  );
}
