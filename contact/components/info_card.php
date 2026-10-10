            <!-- ข้อมูลติดต่อกองบรรณาธิการและผู้พัฒนา (ตามเกณฑ์ Google Play News Policy) -->
            <div class="card">
                <h2 class="card-title">
                    <span class="material-icons">corporate_fare</span>
                    ข้อมูลติดต่อทางการ (Official Contact)
                </h2>

                <div class="contact-row">
                    <div class="contact-icon">
                        <span class="material-icons">badge</span>
                    </div>
                    <div class="contact-content">
                        <div class="label">ผู้ดูแลและกองบรรณาธิการข่าว (Publisher / Editorial Team)</div>
                        <div class="value"><?php echo CONTACT_PUBLISHER_NAME; ?></div>
                    </div>
                </div>

                <div class="contact-row">
                    <div class="contact-icon">
                        <span class="material-icons">email</span>
                    </div>
                    <div class="contact-content">
                        <div class="label">อีเมลติดต่ออย่างเป็นทางการ (Official Email) *</div>
                        <div class="value">
                            <a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a>
                        </div>
                    </div>
                </div>

                <div class="contact-row">
                    <div class="contact-icon">
                        <span class="material-icons">phone</span>
                    </div>
                    <div class="contact-content">
                        <div class="label">หมายเลขโทรศัพท์ติดต่อ (Official Phone Number) *</div>
                        <div class="value">
                            <a href="tel:<?php echo CONTACT_PHONE; ?>"><?php echo CONTACT_PHONE_DISPLAY; ?></a>
                        </div>
                    </div>
                </div>

                <div class="contact-row">
                    <div class="contact-icon">
                        <span class="material-icons">location_on</span>
                    </div>
                    <div class="contact-content">
                        <div class="label">ที่ตั้งสำนักงาน (Editorial Office Address)</div>
                        <div class="value"><?php echo CONTACT_ADDRESS; ?></div>
                    </div>
                </div>

                <div class="contact-row">
                    <div class="contact-icon">
                        <span class="material-icons">schedule</span>
                    </div>
                    <div class="contact-content">
                        <div class="label">เวลาทำการ (Operating Hours)</div>
                        <div class="value"><?php echo CONTACT_OPERATING_HOURS; ?></div>
                    </div>
                </div>

                <?php if (!empty($publisher_content)) { ?>
                    <div style="margin-top: 20px; padding-top: 15px; border-top: 1px dashed var(--border);">
                        <div class="label" style="margin-bottom: 8px;">ข้อมูลจากระบบ (System Details):</div>
                        <div style="font-size: 0.95rem; color: var(--text-sub);">
                            <?php echo $publisher_content; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
