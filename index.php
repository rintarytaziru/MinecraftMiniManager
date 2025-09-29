<?php include "./src/constant.php" ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Minecraft Bedrock Mod Selector</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/3.0.0/uicons-bold-rounded/css/uicons-bold-rounded.css">
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-brands/css/uicons-brands.css'>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=close,warning" />
  <style> ::selection {
    background: #2e7520; /* Màu khi bôi đen */
    color: #fff;
  }
  body {
    background: url(<?= $config["image_background"] ?>);
    background-attachment: fixed;
    background-size: cover;
  } </style>
</head>
<body class="min-h-screen">
  <!-- Bố cục 2 cột -->
  <div class="grid grid-cols-2 gap-1 mb-4 m-4">
    <!-- Resource Packs - Gói tài nguyên -->
    <div class="overflow-y-scroll bg-white/40 max-h-[400px] h-full rounded-md p-1 min-h-[280px]">
      <h2 class="text-xl font-semibold mb-3 p-2">Resource Packs</h2>
      <div id="resource_packs" class="space-y-2"></div>
    </div>

    <!-- Behavior Packs - Gói hành vi -->
    <div class="overflow-y-scroll bg-white/40 max-h-[400px] h-full rounded-md p-1 min-h-[280px]">
      <h2 class="text-xl font-semibold mb-3 p-2">Behavior Packs</h2>
      <div id="behavior_packs" class="space-y-2"></div>
    </div>
  </div>

  <!-- Added - Các gói đã thêm -->
  <div class="grid grid-cols-2 gap-1 m-4">
    <div class="border p-2 bg-white/30 overflow-y-scroll max-h-[400px] h-full rounded-md min-h-[280px]">
      <h3 class="text-lg font-medium mb-2">Resource</h3>
      <div id="added_resource" class="space-y-2">Resource Pack Loading..</div>
    </div>
    <div class="border p-2 bg-white/30 overflow-y-scroll max-h-[400px] h-full rounded-md min-h-[280px]">
      <h3 class="text-lg font-medium mb-2">Behavior</h3>
      <div id="added_behavior" class="space-y-2">Behavior Pack Loading..</div>
    </div>
  </div>

<footer class="w-full bg-gray-900 text-white py-4">
  <div class="max-w-5xl mx-auto flex flex-col md:flex-row justify-between items-center gap-3 text-sm px-4">
    
    <!-- Thông tin chủ sở hữu -->
    <div class="text-center md:text-left">
      Minecraft Mini Addons Manager © 2025 RintaryTaziru. All rights reserved.
    </div>

    <!-- Offical Linls -->
    <div class="flex flex-col md:flex-row items-center gap-2 md:gap-4 text-center">
      <a href="https://github.com/RintaryTaziru" target="_blank" class="flex items-center gap-1 text-blue-400 hover:underline">
        <i class="fi fi-brands-github"></i> Profile
      </a>
      <a href="https://github.com/RintaryTaziru/<Tên-Repo>" target="_blank" class="flex items-center gap-1 text-blue-400 hover:underline">
        <i class="fi fi-brands-github"></i> Repository
      </a>
    </div>

  </div>
</footer>


  <!-- Thư viện JS được sử dụng -->
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

  <script>
    // Bảng màu và hiệu ứng Minecraft
    const mcColors = {
      '0': '#000000', // Black
      '1': '#0000AA', // Dark Blue
      '2': '#00AA00', // Dark Green
      '3': '#00AAAA', // Dark Aqua
      '4': '#AA0000', // Dark Red
      '5': '#AA00AA', // Dark Purple
      '6': '#FFAA00', // Gold
      '7': '#AAAAAA', // Gray
      '8': '#555555', // Dark Gray
      '9': '#5555FF', // Blue
      'a': '#55FF55', // Green
      'b': '#55FFFF', // Aqua
      'c': '#FF5555', // Red
      'd': '#FF55FF', // Light Purple
      'e': '#FFFF55', // Yellow
      'f': '#FFFFFF', // White
    };

    const mcFormats = {
      'l': 'font-weight:bold;', // Bold / In đậm
      'o': 'font-style:italic;', // Italic / In nghiêng
      'n': 'text-decoration:underline;', // Underline / Gạch chân
      'm': 'text-decoration:line-through;', // Strikethrough / Gạch ngang
      'r': 'reset' // Reset / Đặt lại hiệu ứng
    };

    const notyf = new Notyf({
      duration: 2000,
      position: {
        x: 'right',
        y: 'top',
      },
      types: [
        {
          type: 'warn',
          background: 'orange',
          icon: {
            className: 'material-icons',
            tagName: 'i',
            text: 'warning'
          }
        },
        {
          type: 'error',
          background: 'indianred',
          duration: 4000,
          dismissible: true
        },
        {
          type: 'success',
          background: '#36ad18',
        }
      ]
    });

    function mcFormat(text) {
      if (!text) return "";
      let result = "";
      let openTags = [];

      for (let i = 0; i < text.length; i++) {
        if (text[i] === "§" && i + 1 < text.length) {
          let code = text[i + 1].toLowerCase();
          i++;

          if (mcColors[code]) {
            // đóng tag trước đó
            if (openTags.length) {
              result += "</span>";
              openTags.pop();
            }
            let style = `color:${mcColors[code]};`;
            result += `<span style="${style}">`;
            openTags.push("color");
          } else if (mcFormats[code]) {
            if (code === 'r') {
              // reset hết
              while (openTags.length) {
                result += "</span>";
                openTags.pop();
              }
            } else {
              let style = mcFormats[code];
              result += `<span style="${style}">`;
              openTags.push("format");
            }
          }
        } else {
          result += text[i];
        }
      }

      // đóng tag thừa
      while (openTags.length) {
        result += "</span>";
        openTags.pop();
      }

      return result;
    }

    function enableSorting(listId, type) {
      /* Nguyên lý hoạt động:
        - Sử dụng thư viện SortableJS để kéo thả sắp xếp
        - Khi kết thúc kéo thả sẽ dựa vào index cũ và mới để thay đổi vị trí
        - request được sử dụng ở  ./src/package_change.php
      */
      new Sortable(document.getElementById(listId), {
        handle: ".drag-handle", // chỉ kéo bằng icon
        animation: 150,
        onEnd: function (evt) {
          let current = evt.oldIndex + 1; // vị trí cũ
          let newChange = evt.newIndex + 1; // vị trí mới

          // Gửi request đổi vị trí
          let xhttp = new XMLHttpRequest();
          xhttp.open("GET", `./src/package_change.php?type=${type}&current=${current}&newChange=${newChange}`, true);
          xhttp.send();
        }
      });
    }

    function copyPathToClipboard(path) {
      navigator.clipboard.writeText(path).then(() => {
        notyf.success("Đường dẫn đã được sao chép vào clipboard!");
      }).catch(err => {
        notyf.error("Không thể sao chép đường dẫn: " + err);
      });
    }

    function getMinecraftVersion(version) {
      if (!version) return "Không xác định";
      return Array.isArray(version) ? version.join(".") : version;
    }

    function getAuthors(authors) {
      if (!authors || authors.length === 0) return "Không có tác giả.";
      if (Array.isArray(authors)) {
        return authors.map(author => `<a href="https://google.com/search?q=${encodeURIComponent("\"" + author + "\"")}" class="text-blue-500 hover:underline" target="_blank">${mcFormat(author)}</a>`).join(", ");
      }
      return undefined;
    }

    function getPackLink(metadata) {
      return metadata
        ? `<a class="text-blue-400 hover:underline" href="${metadata}">${metadata}</a>`
        : "Không có liên kết";
    }


    function showInfo(pack) {
      let xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          let data = JSON.parse(this.responseText);
          let header = data.header || {};
          let iconPath = data.icon || "default-icon.png"; // nếu header ko có icon thì dùng default
          let metadata = data.metadata || {};

          let versionText = Array.isArray(header.version) ? header.version.join(".") : "Không xác định";
          let info = `
            <div class="p-4 bg-white rounded-md flex items-center">
              <img src="${iconPath}" alt="Icon" class="w-40 h-40 mr-3 rounded-md">
              <div>
                <h3 class="text-lg font-semibold mb-1">${mcFormat(header.name || data.pack_id)}</h3>
                <p class="text-sm text-gray-700" style="line-height: 35px;"><strong>ID:</strong> ${header.uuid || "Không xác định"}</p>
                <hr/>
                <p class="text-sm text-gray-700" style="line-height: 35px;"><strong>Phiên bản:</strong> ${versionText}</p>
                <hr/>
                <p class="text-sm text-gray-700" style="line-height: 35px;"><strong>Mô tả:</strong> ${header.description || "Không có mô tả."}</p>
                <hr/>
                <p class="text-sm text-gray-700" style="line-height: 35px;"><strong>Tác giả:</strong> ${getAuthors(metadata.authors) || "Không có tác giả."}</p>
              </div>
            </div>
            <hr/>
            <div>
              <p class="text-sm inline-block text-gray-700 mr-2" style="line-height: 35px;"><strong>Đường dẫn thư mục:</strong> ${data.path || "Không có đường dẫn."}</p>
              <button class="mr-2 px-3 inline-block py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded" onclick="copyPathToClipboard('${data.path || ""}')">
                Sao chép
              </button>
              <hr/>
              <p class="text-sm text-gray-700" style="line-height: 35px;"><strong>Phiên bản Minecraft tối thiểu:</strong> ${getMinecraftVersion(header.min_engine_version) || "Không xác định"}</p>
              <hr/>
              <p class="text-sm text-gray-700" style="line-height: 35px;"><strong>Luật bản quyền:</strong> ${metadata.license || "Không xác định"}</p>
              <hr/>
              <p class="text-sm text-gray-700" style="line-height: 35px;"><strong>Liên kết:</strong> ${getPackLink(metadata?.url)}</p>
              
            </div>
          `;

          // Hiển thị thông tin chi tiết trong bản thông tin gói
          let modal = document.createElement("div");
          modal.className = "fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50";
          modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-[800px] w-full relative">
              <button id="closeModalBtn" class="absolute transition duration-200 top-4 right-4 text-sm text-gray-500 p-3 rounded-md w-10 h-10 hover:text-gray-700 hover:bg-gray-300"><i class="fi fi-br-cross"></i></button>
              ${mcFormat(info)}
            </div>
          `;

          // làm mượt bằng opacity khi mở
          var modalDelay = 100; // thời gian chờ trước khi bắt đầu hiệu ứng

          modal.style.opacity = 0;
          modal.style.transition = "opacity 0.2s ease";
          document.body.appendChild(modal);
          setTimeout(() => {
            modal.style.opacity = 1;
          }, modalDelay);

          // làm mượt bằng opacity khi đóng
          modal.querySelector("#closeModalBtn").addEventListener("click", () => {
            modal.style.transition = "opacity 0.2s ease";
            modal.style.opacity = 0;
            setTimeout(() => modal.remove(), modalDelay);
          });
        }
      };

      // Lấy thông tin chi tiết gói
      xhttp.open("GET", `./src/package_info.php?uid=${encodeURIComponent(pack.uuid)}`, true);
      xhttp.send();
    }

    function loadAll() {
      let xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          let data = JSON.parse(this.responseText);
          renderList(data.resource, "resource_packs", true);
          renderList(data.behavior, "behavior_packs", true);
        }
      };
      xhttp.open("GET", "./src/package_all_list.php", true);
      xhttp.send();
    }

    function renderList(packs, targetId, isAll) {
      let container = document.getElementById(targetId);
      container.innerHTML = "";
      // Làm trống danh sách trước khi thêm mới

      packs.forEach(p => {
        let icon = p.icon
          ? `<img src="${p.icon}" class="w-10 h-10 object-cover rounded-md">`
          : `<div class="w-10 h-10 bg-gray-300 flex items-center justify-center rounded-md text-xs">No</div>`;
        let desc = isAll
          ? `(ID: ${p.uuid}) v${p.version.join(".")}`
          : `v${p.version.join(".")}`;

        // biểu tượng handle có thể kéo
        let handle = isAll ? "" : `<span class="drag-handle cursor-move mr-2">⋮⋮</span>`;

        let div = document.createElement("div");
        div.className = "flex items-center bg-white/60 rounded-lg shadow p-2 ";
        div.innerHTML = `
          ${handle}
          ${icon}
          <div class="ml-3 flex-1">
            <div class="text-sm font-semibold">${mcFormat(p.name || p.pack_id)}</div>
            <div class="text-xs text-gray-600">${mcFormat(desc)}</div>
          </div>
          <button id="info_button" class="ml-1 w-5 h-5 text-xs text-gray-500 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center">
            i
          </button>
          <button id="pack_button" class="ml-2 px-2 py-1 text-sm ${isAll ? 'bg-blue-600 hover:bg-blue-700' : 'bg-red-600 hover:bg-red-700'} text-white rounded">
            ${isAll ? 'Thêm' : 'Gỡ'}
          </button>
        `;

        container.appendChild(div);

        // lắn nghe sự kiện khi thêm/gỡ
        let btn = div.querySelector("#pack_button");
        if (isAll) {
          btn.addEventListener("click", () => addPack(encodeURIComponent(JSON.stringify(p))));
        } else {
          btn.addEventListener("click", () => removePack(encodeURIComponent(JSON.stringify(p))));
        }

        let infoBtn = div.querySelector("#info_button");
        infoBtn.addEventListener("click", () => showInfo(p));
      });

      // Nếu là danh sách đã thêm thì bật drag-sort
      if (!isAll) {
        enableSorting(targetId, targetId.includes("resource") ? "resource" : "behavior");
      }
    }

    function loadAdded() {
      let xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          let data = JSON.parse(this.responseText);
          renderList(data.resource, "added_resource", false);
          renderList(data.behavior, "added_behavior", false);
        }
      };
      xhttp.open("GET", "./src/package_added_list.php", true);
      xhttp.send();
    }

    function addPack(data) {
      let xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          if (this.responseText.trim() == "true") {
            loadAdded();
            notyf.success("Gói đã được thêm vào.");
          } else if (this.responseText.trim() == "duplicated") {
            notyf.open({
              type: 'warn',
              message: 'Gói này đã tồn tại.'
            });
          } else {
            notyf.error("Không thể thêm gói này. Vui lòng thử lại sau.");
          }
        }
      };
      xhttp.open("GET", "./src/package_add.php?data=" + data, true);
      xhttp.send();
    }

    function removePack(data) {
      let xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          if (this.responseText.trim() == "true") {
            loadAdded();
            notyf.success("Gói đã được gỡ bỏ.");
          } else {
            notyf.error("Không thể gỡ bỏ gói này. Vui lòng thử lại sau.");
          }
        } else if (this.readyState == 4) {
          notyf.error("Lỗi khi gỡ bỏ gói: " + this.responseText);
        }
      };
      xhttp.open("GET", "./src/package_remove.php?data=" + data, true);
      xhttp.send();
    }

    loadAll();
    loadAdded();

    /*
      -- Hết --
    */
  </script>
</body>
</html>
