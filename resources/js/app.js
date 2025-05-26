import "./bootstrap";
import Alpine from "alpinejs";
import Calendar from "tui-calendar";
import "tui-calendar/dist/tui-calendar.css";

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    const calendarEl = document.getElementById("calendar");

    if (!calendarEl) return;

    const userId = calendarEl.dataset.userId;

    console.log("✅ app.js yüklendi ve calendar div bulundu");

    const calendar = new Calendar("#calendar", {
        changeView: "month",
        useCreationPopup: false, // ✅ kendi popup'ı
        useDetailPopup: true,
        taskView: false,
        isReadOnly: false, // ❗ KAPALI (ki kullanıcı etkinlik ekleyebilsin)
        disableDblClick: true, // 🛡️ Çift tıklamayla etkinlik eklenmesin
        disableClick: false, // 🟢 Tıklama ile detay popup açılsın
        scheduleView: ["time"],
    });

    calendar.setCalendars([
        {
            id: "1",
            name: "Varsayılan",
            color: "#ffffff",
            bgColor: "#2563eb",
            borderColor: "#2563eb",
        },
    ]);

    let pendingEventData = null;

    calendar.on("beforeCreateSchedule", function (event) {
        pendingEventData = event;
        showCustomPopup();
    });

    function toDatetimeLocalString(date) {
        const d = new Date(date);
        d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
        return d.toISOString().slice(0, 16);
    }

    function showCustomPopup() {
        document.getElementById("customPopup").classList.remove("hidden");

        // varsayılan değerleri inputlara yerleştir
        document.getElementById("customTitle").value = "";
        document.getElementById("customDescription").value = "";
        document.getElementById("customStart").value = toDatetimeLocalString(
            pendingEventData.start
        );
        document.getElementById("customEnd").value = toDatetimeLocalString(
            pendingEventData.end
        );
    }

    function hideCustomPopup() {
        document.getElementById("customPopup").classList.add("hidden");
        document.getElementById("customTitle").value = "";
        document.getElementById("customDescription").value = "";
        document.getElementById("customStart").value = "";
        document.getElementById("customEnd").value = "";
    }

    document.getElementById("cancelBtn").addEventListener("click", () => {
        hideCustomPopup();
    });

    document.getElementById("saveBtn").addEventListener("click", () => {
        const title = document.getElementById("customTitle").value;
        const description = document.getElementById("customDescription").value;
        const start = document.getElementById("customStart").value;
        const end = document.getElementById("customEnd").value;
        const newEvent = data.event;

        // Takvime ekle
        calendar.createSchedules([
            {
                id: String(newEvent.id),
                calendarId: "1",
                title,
                category: "time",
                start,
                end,
                raw: { description },
            },
        ]);

        // Veritabanına kaydet
        const formData = new FormData();
        formData.append("title", title);
        formData.append("description", description);
        formData.append("start", start);
        formData.append("end", end);

        fetch("/events/store", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: formData,
        })
            .then((res) => res.json())
            .then((data) => {
                console.log("✅ Etkinlik başarıyla kaydedildi", data);
                hideCustomPopup();
            })
            .catch((err) => {
                console.error("❌ Kayıt hatası:", err);
            });
    });

    window.changeView = function (viewName) {
        calendar.changeView(viewName, true);
        console.log(`👁️ Görünüm değişti: ${viewName}`);
    };

    calendar.on("beforeUpdateSchedule", function (event) {
        const schedule = event.schedule;
        const changes = event.changes;

        console.log("✏️ Edit tıklandı", schedule);

        // Kendi popup'ını açmak istersen burada açarsın
        showEditPopup(schedule);

        // ❌ preventDefault() çağırma — bu fonksiyonda yok!
    });

    function toDatetimeLocalString(date) {
        const d = new Date(date);
        d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
        return d.toISOString().slice(0, 16);
    }

    function showEditPopup(schedule) {
        document.getElementById("editId").value = schedule.id;
        document.getElementById("editTitle").value = schedule.title;
        document.getElementById("editDescription").value =
            schedule.raw?.description ?? "";
        document.getElementById("editStart").value = toDatetimeLocalString(
            schedule.start
        );
        document.getElementById("editEnd").value = toDatetimeLocalString(
            schedule.end
        );

        document.getElementById("editPopup").classList.remove("hidden");
    }

    function hideEditPopup() {
        document.getElementById("editPopup").classList.add("hidden");
    }

    document.getElementById("editSaveBtn").addEventListener("click", () => {
        const id = document.getElementById("editId").value;
        const title = document.getElementById("editTitle").value;
        const description = document.getElementById("editDescription").value;
        const start = document.getElementById("editStart").value;
        const end = document.getElementById("editEnd").value;

        fetch(`/events/update/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({
                title,
                description,
                start,
                end,
            }),
        })
            .then((res) => res.json())
            .then((data) => {
                console.log("✅ Güncellendi:", data);

                calendar.updateSchedule(id, "1", {
                    title,
                    start,
                    end,
                    raw: { description },
                });

                location.reload();

                hideEditPopup();
            })
            .catch((err) => {
                console.error("❌ Güncelleme hatası:", err);
            });
    });

    document
        .getElementById("editCancelBtn")
        .addEventListener("click", hideEditPopup);

    calendar.on("beforeDeleteSchedule", function (event) {
        const { schedule } = event;

        fetch(`/events/delete/${schedule.id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
        })
            .then((res) => res.json())
            .then((data) => {
                console.log("🗑️ Silindi", data);
                calendar.deleteSchedule(schedule.id, schedule.calendarId);
            })
            .catch((err) => {
                console.error("❌ Silinemedi", err);
            });
    });

    const fetchUrl = userId
        ? `/admin/events/${userId}` // admin başka kullanıcıya bakıyor
        : `/events/list`; // normal kullanıcı kendi takvimine bakıyor

    // Var olan etkinlikleri yükle
    fetch(fetchUrl)
        .then((res) => res.json())
        .then((data) => {
            console.log("📥 Etkinlikler yüklendi", data);
            calendar.createSchedules(data);
        });
});
