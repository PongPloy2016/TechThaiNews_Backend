            <!-- แบบฟอร์มส่งข้อความติดต่อเรา (เชื่อมต่อ Supabase) -->
            <div class="card">
                <h2 class="card-title">
                    <span class="material-icons">send</span>
                    ส่งข้อความติดต่อเรา (Send Message)
                </h2>

                <?php if ($send_status === 'success') { ?>
                    <div class="alert-success">
                        <span class="material-icons">check_circle</span>
                        <span><?php echo htmlspecialchars($status_msg); ?></span>
                    </div>
                <?php } elseif ($send_status === 'error') { ?>
                    <div class="alert-error">
                        <span class="material-icons">error_outline</span>
                        <span><?php echo htmlspecialchars($status_msg); ?></span>
                    </div>
                <?php } ?>

                <form method="post" action="">
                    <div class="form-group">
                        <label for="name">ชื่อ - นามสกุล หรือชื่อองค์กร *</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="กรอกชื่อของคุณ" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">อีเมลของคุณ *</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="subject">หัวข้อเรื่อง *</label>
                        <input type="text" id="subject" name="subject" class="form-control" placeholder="เช่น แจ้งแก้ไขข้อมูลข่าว, ติดต่อสอบถาม" value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="message">รายละเอียดข้อความ *</label>
                        <textarea id="message" name="message" class="form-control" placeholder="พิมพ์ข้อความของคุณที่นี่..." required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" name="btnSendContact" class="btn-submit">
                        <span class="material-icons">send</span>
                        <span>ส่งข้อความ (Submit Message)</span>
                    </button>
                </form>
            </div>
