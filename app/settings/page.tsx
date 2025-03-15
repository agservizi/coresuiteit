"use client"

import { useState, useEffect } from "react"
import { useStore } from "@/lib/store"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { Switch } from "@/components/ui/switch"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Badge } from "@/components/ui/badge"
import { Separator } from "@/components/ui/separator"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import { Save, Upload, Download, Trash2, Edit, Plus, AlertTriangle, CheckCircle } from "lucide-react"
import { toast } from "@/hooks/use-toast"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog"

export default function SettingsPage() {
  const { settings, updateSettings, users, addUser, updateUser, deleteUser, isInitialized, initializeStore } =
    useStore()

  const [companySettings, setCompanySettings] = useState({
    companyName: "",
    address: "",
    phone: "",
    email: "",
    vat: "",
    logo: "",
  })

  const [appSettings, setAppSettings] = useState({
    theme: "light",
    currency: "EUR",
    language: "it",
  })

  const [notificationSettings, setNotificationSettings] = useState({
    lowStock: true,
    newSales: true,
    email: false,
  })

  const [newUser, setNewUser] = useState({
    name: "",
    email: "",
    role: "seller",
    status: "active",
  })

  // Modifica la definizione dello stato dell'utente in modifica
  const [editingUser, setEditingUser] = useState<any>(null)
  const [isAddUserDialogOpen, setIsAddUserDialogOpen] = useState(false)
  const [isEditUserDialogOpen, setIsEditUserDialogOpen] = useState(false)
  const [isDeleteUserDialogOpen, setIsDeleteUserDialogOpen] = useState(false)
  const [userToDelete, setUserToDelete] = useState(null)

  // Inizializza lo store se non è già stato fatto
  useEffect(() => {
    if (!isInitialized) {
      initializeStore()
    }

    // Assicuriamoci che lo store sia inizializzato prima di caricare le impostazioni
    if (settings && Object.keys(settings).length > 0) {
      setCompanySettings({
        companyName: settings.companyName || "",
        address: settings.address || "",
        phone: settings.phone || "",
        email: settings.email || "",
        vat: settings.vat || "",
        logo: settings.logo || "",
      })

      setAppSettings({
        theme: settings.theme || "light",
        currency: settings.currency || "EUR",
        language: settings.language || "it",
      })

      setNotificationSettings({
        lowStock: settings.notifications?.lowStock ?? true,
        newSales: settings.notifications?.newSales ?? true,
        email: settings.notifications?.email ?? false,
      })
    }
  }, [isInitialized, initializeStore, settings])

  // Salva le impostazioni dell'azienda
  const saveCompanySettings = () => {
    try {
      // Verifica che i campi obbligatori siano compilati
      if (!companySettings.companyName.trim()) {
        toast({
          title: "Errore",
          description: "Il nome dell'azienda è obbligatorio.",
          variant: "destructive",
        })
        return
      }

      // Aggiorna le impostazioni nello store
      updateSettings({
        companyName: companySettings.companyName,
        address: companySettings.address,
        phone: companySettings.phone,
        email: companySettings.email,
        vat: companySettings.vat,
        logo: companySettings.logo,
      })

      // Aggiorna lo stato locale per riflettere le modifiche
      setCompanySettings({
        companyName: companySettings.companyName,
        address: companySettings.address,
        phone: companySettings.phone,
        email: companySettings.email,
        vat: companySettings.vat,
        logo: companySettings.logo,
      })

      toast({
        title: "Impostazioni aziendali salvate",
        description: "Le impostazioni dell'azienda sono state aggiornate con successo.",
        variant: "success",
      })
    } catch (error) {
      console.error("Errore nel salvataggio delle impostazioni aziendali:", error)
      toast({
        title: "Errore",
        description: "Si è verificato un errore durante il salvataggio delle impostazioni.",
        variant: "destructive",
      })
    }
  }

  // Salva le impostazioni dell'applicazione
  const saveAppSettings = () => {
    try {
      // Aggiorna le impostazioni nello store
      updateSettings({
        theme: appSettings.theme,
        currency: appSettings.currency,
        language: appSettings.language,
      })

      // Aggiorna lo stato locale per riflettere le modifiche
      setAppSettings({
        theme: appSettings.theme,
        currency: appSettings.currency,
        language: appSettings.language,
      })

      // Applica il tema immediatamente
      if (appSettings.theme === "dark") {
        document.documentElement.classList.add("dark")
      } else if (appSettings.theme === "light") {
        document.documentElement.classList.remove("dark")
      } else {
        // System
        if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
          document.documentElement.classList.add("dark")
        } else {
          document.documentElement.classList.remove("dark")
        }
      }

      toast({
        title: "Impostazioni applicazione salvate",
        description: "Le impostazioni dell'applicazione sono state aggiornate con successo.",
        variant: "success",
      })
    } catch (error) {
      console.error("Errore nel salvataggio delle impostazioni dell'applicazione:", error)
      toast({
        title: "Errore",
        description: "Si è verificato un errore durante il salvataggio delle impostazioni.",
        variant: "destructive",
      })
    }
  }

  // Salva le impostazioni delle notifiche
  const saveNotificationSettings = () => {
    try {
      // Aggiorna le impostazioni nello store
      updateSettings({
        notifications: {
          lowStock: notificationSettings.lowStock,
          newSales: notificationSettings.newSales,
          email: notificationSettings.email,
        },
      })

      // Aggiorna lo stato locale per riflettere le modifiche
      setNotificationSettings({
        lowStock: notificationSettings.lowStock,
        newSales: notificationSettings.newSales,
        email: notificationSettings.email,
      })

      toast({
        title: "Impostazioni notifiche salvate",
        description: "Le impostazioni delle notifiche sono state aggiornate con successo.",
        variant: "success",
      })
    } catch (error) {
      console.error("Errore nel salvataggio delle impostazioni delle notifiche:", error)
      toast({
        title: "Errore",
        description: "Si è verificato un errore durante il salvataggio delle impostazioni.",
        variant: "destructive",
      })
    }
  }

  // Aggiungi un nuovo utente
  const handleAddUser = () => {
    if (!newUser.name || !newUser.email) {
      toast({
        title: "Errore",
        description: "Nome e email sono campi obbligatori.",
        variant: "destructive",
      })
      return
    }

    try {
      addUser({
        name: newUser.name,
        email: newUser.email,
        role: newUser.role,
        status: newUser.status,
        avatar: "/placeholder.svg?height=36&width=36",
        lastLogin: new Date().toISOString().split("T")[0],
      })

      setNewUser({
        name: "",
        email: "",
        role: "seller",
        status: "active",
      })

      setIsAddUserDialogOpen(false)

      toast({
        title: "Utente aggiunto",
        description: "Il nuovo utente è stato aggiunto con successo.",
        variant: "success",
      })
    } catch (error) {
      console.error("Errore nell'aggiunta dell'utente:", error)
      toast({
        title: "Errore",
        description: "Si è verificato un errore durante l'aggiunta dell'utente.",
        variant: "destructive",
      })
    }
  }

  // Modifica un utente
  const handleEditUser = () => {
    if (!editingUser || !editingUser.name || !editingUser.email) {
      toast({
        title: "Errore",
        description: "Nome e email sono campi obbligatori.",
        variant: "destructive",
      })
      return
    }

    try {
      updateUser(editingUser)
      setIsEditUserDialogOpen(false)

      toast({
        title: "Utente aggiornato",
        description: "L'utente è stato aggiornato con successo.",
        variant: "success",
      })
    } catch (error) {
      console.error("Errore nell'aggiornamento dell'utente:", error)
      toast({
        title: "Errore",
        description: "Si è verificato un errore durante l'aggiornamento dell'utente.",
        variant: "destructive",
      })
    }
  }

  // Elimina un utente
  const handleDeleteUser = () => {
    if (userToDelete) {
      try {
        deleteUser(userToDelete.id)
        setIsDeleteUserDialogOpen(false)
        setUserToDelete(null)

        toast({
          title: "Utente eliminato",
          description: "L'utente è stato eliminato con successo.",
          variant: "success",
        })
      } catch (error) {
        console.error("Errore nell'eliminazione dell'utente:", error)
        toast({
          title: "Errore",
          description: "Si è verificato un errore durante l'eliminazione dell'utente.",
          variant: "destructive",
        })
      }
    }
  }

  // Esporta i dati
  const handleExportData = () => {
    try {
      const data = {
        products: useStore.getState().products,
        sales: useStore.getState().sales,
        customers: useStore.getState().customers,
        suppliers: useStore.getState().suppliers,
        users: useStore.getState().users,
        settings: useStore.getState().settings,
        notifications: useStore.getState().notifications,
      }

      const blob = new Blob([JSON.stringify(data, null, 2)], { type: "application/json" })
      const url = URL.createObjectURL(blob)
      const a = document.createElement("a")
      a.href = url
      a.download = `telestore-backup-${new Date().toISOString().split("T")[0]}.json`
      document.body.appendChild(a)
      a.click()
      document.body.removeChild(a)
      URL.revokeObjectURL(url)

      toast({
        title: "Dati esportati",
        description: "I dati sono stati esportati con successo.",
        variant: "success",
      })
    } catch (error) {
      console.error("Errore nell'esportazione dei dati:", error)
      toast({
        title: "Errore",
        description: "Si è verificato un errore durante l'esportazione dei dati.",
        variant: "destructive",
      })
    }
  }

  // Importa i dati
  const handleImportData = (event) => {
    const file = event.target.files[0]
    if (!file) return

    const reader = new FileReader()
    reader.onload = (e) => {
      try {
        const data = JSON.parse(e.target.result as string)

        // Verifica che il file contenga i dati corretti
        if (!data.products || !data.sales || !data.customers || !data.suppliers || !data.settings) {
          throw new Error("Il file non contiene dati validi")
        }

        // Aggiorna lo store con i dati importati
        useStore.setState({
          products: data.products,
          sales: data.sales,
          customers: data.customers,
          suppliers: data.suppliers,
          users: data.users || useStore.getState().users,
          settings: data.settings,
          notifications: data.notifications || useStore.getState().notifications,
          isInitialized: true,
        })

        // Aggiorna lo stato locale con le nuove impostazioni
        setCompanySettings({
          companyName: data.settings.companyName || "",
          address: data.settings.address || "",
          phone: data.settings.phone || "",
          email: data.settings.email || "",
          vat: data.settings.vat || "",
          logo: data.settings.logo || "",
        })

        setAppSettings({
          theme: data.settings.theme || "light",
          currency: data.settings.currency || "EUR",
          language: data.settings.language || "it",
        })

        setNotificationSettings({
          lowStock: data.settings.notifications?.lowStock ?? true,
          newSales: data.settings.notifications?.newSales ?? true,
          email: data.settings.notifications?.email ?? false,
        })

        toast({
          title: "Dati importati",
          description: "I dati sono stati importati con successo.",
          variant: "success",
        })
      } catch (error) {
        console.error("Errore nell'importazione dei dati:", error)
        toast({
          title: "Errore",
          description: "Si è verificato un errore durante l'importazione dei dati.",
          variant: "destructive",
        })
      }
    }
    reader.readAsText(file)

    // Reset input file
    event.target.value = null
  }

  useEffect(() => {
    // Inizializza lo store se non è già stato fatto
    if (!isInitialized) {
      initializeStore()
    }
  }, [isInitialized, initializeStore])

  // Aggiungi un nuovo useEffect separato per il caricamento delle impostazioni
  useEffect(() => {
    // Assicuriamoci che lo store sia inizializzato prima di caricare le impostazioni
    if (settings && Object.keys(settings).length > 0) {
      // Carica le impostazioni dell'azienda
      setCompanySettings({
        companyName: settings.companyName || "",
        address: settings.address || "",
        phone: settings.phone || "",
        email: settings.email || "",
        vat: settings.vat || "",
        logo: settings.logo || "",
      })

      // Carica le impostazioni dell'applicazione
      setAppSettings({
        theme: settings.theme || "light",
        currency: settings.currency || "EUR",
        language: settings.language || "it",
      })

      // Carica le impostazioni delle notifiche
      setNotificationSettings({
        lowStock: settings.notifications?.lowStock ?? true,
        newSales: settings.notifications?.newSales ?? true,
        email: settings.notifications?.email ?? false,
      })

      // Applica il tema corrente
      if (settings.theme === "dark") {
        document.documentElement.classList.add("dark")
      } else if (settings.theme === "light") {
        document.documentElement.classList.remove("dark")
      } else if (settings.theme === "system") {
        // System
        if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
          document.documentElement.classList.add("dark")
        } else {
          document.documentElement.classList.remove("dark")
        }
      }
    }
  }, [settings])

  return (
    <div className="flex min-h-screen w-full flex-col">
      <main className="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h1 className="text-3xl font-bold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-primary to-primary/70">
            Impostazioni
          </h1>
        </div>

        <Tabs defaultValue="company" className="space-y-4">
          <TabsList className="bg-muted/50 p-1 w-full overflow-x-auto">
            <TabsTrigger value="company" className="data-[state=active]:bg-white data-[state=active]:shadow-sm">
              Azienda
            </TabsTrigger>
            <TabsTrigger value="application" className="data-[state=active]:bg-white data-[state=active]:shadow-sm">
              Applicazione
            </TabsTrigger>
            <TabsTrigger value="users" className="data-[state=active]:bg-white data-[state=active]:shadow-sm">
              Utenti
            </TabsTrigger>
            <TabsTrigger value="notifications" className="data-[state=active]:bg-white data-[state=active]:shadow-sm">
              Notifiche
            </TabsTrigger>
            <TabsTrigger value="backup" className="data-[state=active]:bg-white data-[state=active]:shadow-sm">
              Backup
            </TabsTrigger>
          </TabsList>

          {/* Impostazioni Azienda */}
          <TabsContent value="company" className="space-y-4">
            <Card className="border-none shadow-md dashboard-card">
              <CardHeader>
                <CardTitle>Informazioni Aziendali</CardTitle>
                <CardDescription>Gestisci le informazioni della tua azienda</CardDescription>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div className="space-y-2">
                    <Label htmlFor="companyName">Nome Azienda</Label>
                    <Input
                      id="companyName"
                      value={companySettings.companyName}
                      onChange={(e) => setCompanySettings({ ...companySettings, companyName: e.target.value })}
                      className="border-muted-foreground/20 focus-visible:ring-primary"
                    />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="vat">Partita IVA</Label>
                    <Input
                      id="vat"
                      value={companySettings.vat}
                      onChange={(e) => setCompanySettings({ ...companySettings, vat: e.target.value })}
                      className="border-muted-foreground/20 focus-visible:ring-primary"
                    />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="address">Indirizzo</Label>
                    <Input
                      id="address"
                      value={companySettings.address}
                      onChange={(e) => setCompanySettings({ ...companySettings, address: e.target.value })}
                      className="border-muted-foreground/20 focus-visible:ring-primary"
                    />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="phone">Telefono</Label>
                    <Input
                      id="phone"
                      value={companySettings.phone}
                      onChange={(e) => setCompanySettings({ ...companySettings, phone: e.target.value })}
                      className="border-muted-foreground/20 focus-visible:ring-primary"
                    />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="email">Email</Label>
                    <Input
                      id="email"
                      type="email"
                      value={companySettings.email}
                      onChange={(e) => setCompanySettings({ ...companySettings, email: e.target.value })}
                      className="border-muted-foreground/20 focus-visible:ring-primary"
                    />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="logo">Logo URL</Label>
                    <Input
                      id="logo"
                      value={companySettings.logo}
                      onChange={(e) => setCompanySettings({ ...companySettings, logo: e.target.value })}
                      className="border-muted-foreground/20 focus-visible:ring-primary"
                    />
                  </div>
                </div>

                <Separator className="my-4" />

                <div className="flex items-center gap-4">
                  <div className="text-sm text-muted-foreground">Anteprima Logo:</div>
                  <div className="h-16 w-16 overflow-hidden rounded-md border p-1 bg-white shadow-sm">
                    <img
                      src={companySettings.logo || "/placeholder.svg?height=64&width=64"}
                      alt="Logo aziendale"
                      className="h-full w-full object-contain"
                    />
                  </div>
                </div>
              </CardContent>
              <CardFooter className="justify-end">
                <Button onClick={saveCompanySettings} className="shadow-md hover:shadow-lg transition-all">
                  <Save className="mr-2 h-4 w-4" />
                  Salva Impostazioni
                </Button>
              </CardFooter>
            </Card>
          </TabsContent>

          {/* Gestione Utenti */}
          <TabsContent value="users" className="space-y-4">
            <Card className="border-none shadow-md dashboard-card">
              <CardHeader className="flex flex-row items-center justify-between">
                <div>
                  <CardTitle>Gestione Utenti</CardTitle>
                  <CardDescription>Gestisci gli utenti che hanno accesso al sistema</CardDescription>
                </div>
                <Dialog open={isAddUserDialogOpen} onOpenChange={setIsAddUserDialogOpen}>
                  <DialogTrigger asChild>
                    <Button className="shadow-md hover:shadow-lg transition-all">
                      <Plus className="mr-2 h-4 w-4" />
                      Nuovo Utente
                    </Button>
                  </DialogTrigger>
                  <DialogContent>
                    <DialogHeader>
                      <DialogTitle>Aggiungi Nuovo Utente</DialogTitle>
                      <DialogDescription>Inserisci i dettagli per creare un nuovo utente</DialogDescription>
                    </DialogHeader>
                    <div className="space-y-4 py-4">
                      <div className="space-y-2">
                        <Label htmlFor="name">Nome Completo</Label>
                        <Input
                          id="name"
                          value={newUser.name}
                          onChange={(e) => setNewUser({ ...newUser, name: e.target.value })}
                          className="border-muted-foreground/20"
                        />
                      </div>
                      <div className="space-y-2">
                        <Label htmlFor="email">Email</Label>
                        <Input
                          id="email"
                          type="email"
                          value={newUser.email}
                          onChange={(e) => setNewUser({ ...newUser, email: e.target.value })}
                          className="border-muted-foreground/20"
                        />
                      </div>
                      <div className="space-y-2">
                        <Label htmlFor="role">Ruolo</Label>
                        <Select value={newUser.role} onValueChange={(value) => setNewUser({ ...newUser, role: value })}>
                          <SelectTrigger id="role" className="border-muted-foreground/20">
                            <SelectValue placeholder="Seleziona ruolo" />
                          </SelectTrigger>
                          <SelectContent>
                            <SelectItem value="admin">Amministratore</SelectItem>
                            <SelectItem value="manager">Manager</SelectItem>
                            <SelectItem value="seller">Venditore</SelectItem>
                          </SelectContent>
                        </Select>
                      </div>
                      <div className="space-y-2">
                        <Label htmlFor="status">Stato</Label>
                        <Select
                          value={newUser.status}
                          onValueChange={(value) => setNewUser({ ...newUser, status: value })}
                        >
                          <SelectTrigger id="status" className="border-muted-foreground/20">
                            <SelectValue placeholder="Seleziona stato" />
                          </SelectTrigger>
                          <SelectContent>
                            <SelectItem value="active">Attivo</SelectItem>
                            <SelectItem value="inactive">Inattivo</SelectItem>
                          </SelectContent>
                        </Select>
                      </div>
                    </div>
                    <DialogFooter>
                      <Button variant="outline" onClick={() => setIsAddUserDialogOpen(false)}>
                        Annulla
                      </Button>
                      <Button onClick={handleAddUser} className="shadow-sm">
                        Aggiungi Utente
                      </Button>
                    </DialogFooter>
                  </DialogContent>
                </Dialog>
              </CardHeader>
              <CardContent>
                <div className="rounded-lg border border-muted-foreground/10 overflow-hidden">
                  <Table>
                    <TableHeader className="bg-muted/30">
                      <TableRow>
                        <TableHead>Utente</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Ruolo</TableHead>
                        <TableHead>Ultimo Accesso</TableHead>
                        <TableHead>Stato</TableHead>
                        <TableHead className="text-right">Azioni</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {users.map((user) => (
                        <TableRow key={user.id} className="hover:bg-muted/20">
                          <TableCell>
                            <div className="flex items-center gap-3">
                              <Avatar className="h-10 w-10 border-2 border-primary/10">
                                <AvatarImage src={user.avatar} alt={user.name} />
                                <AvatarFallback className="bg-primary/10 text-primary">
                                  {user.name
                                    .split(" ")
                                    .map((n) => n[0])
                                    .join("")}
                                </AvatarFallback>
                              </Avatar>
                              <div>{user.name}</div>
                            </div>
                          </TableCell>
                          <TableCell>{user.email}</TableCell>
                          <TableCell>
                            {user.role === "admin" && "Amministratore"}
                            {user.role === "manager" && "Manager"}
                            {user.role === "seller" && "Venditore"}
                          </TableCell>
                          <TableCell>{user.lastLogin || "Mai"}</TableCell>
                          <TableCell>
                            {user.status === "active" ? (
                              <Badge className="bg-green-500 hover:bg-green-600">Attivo</Badge>
                            ) : (
                              <Badge className="bg-gray-500 hover:bg-gray-600">Inattivo</Badge>
                            )}
                          </TableCell>
                          <TableCell className="text-right">
                            <div className="flex justify-end gap-2">
                              <Dialog
                                open={isEditUserDialogOpen && editingUser?.id === user.id}
                                onOpenChange={(open) => {
                                  setIsEditUserDialogOpen(open)
                                  if (open) setEditingUser(user)
                                }}
                              >
                                <DialogTrigger asChild>
                                  <Button variant="ghost" size="icon" className="hover:bg-primary/10">
                                    <Edit className="h-4 w-4 text-primary" />
                                    <span className="sr-only">Modifica</span>
                                  </Button>
                                </DialogTrigger>
                                <DialogContent>
                                  <DialogHeader>
                                    <DialogTitle>Modifica Utente</DialogTitle>
                                    <DialogDescription>Modifica i dettagli dell'utente</DialogDescription>
                                  </DialogHeader>
                                  {editingUser && (
                                    <div className="space-y-4 py-4">
                                      <div className="space-y-2">
                                        <Label htmlFor="edit-name">Nome Completo</Label>
                                        <Input
                                          id="edit-name"
                                          value={editingUser.name}
                                          onChange={(e) => setEditingUser({ ...editingUser, name: e.target.value })}
                                          className="border-muted-foreground/20"
                                        />
                                      </div>
                                      <div className="space-y-2">
                                        <Label htmlFor="edit-email">Email</Label>
                                        <Input
                                          id="edit-email"
                                          type="email"
                                          value={editingUser.email}
                                          onChange={(e) => setEditingUser({ ...editingUser, email: e.target.value })}
                                          className="border-muted-foreground/20"
                                        />
                                      </div>
                                      <div className="space-y-2">
                                        <Label htmlFor="edit-role">Ruolo</Label>
                                        <Select
                                          value={editingUser.role}
                                          onValueChange={(value) => setEditingUser({ ...editingUser, role: value })}
                                        >
                                          <SelectTrigger id="edit-role" className="border-muted-foreground/20">
                                            <SelectValue placeholder="Seleziona ruolo" />
                                          </SelectTrigger>
                                          <SelectContent>
                                            <SelectItem value="admin">Amministratore</SelectItem>
                                            <SelectItem value="manager">Manager</SelectItem>
                                            <SelectItem value="seller">Venditore</SelectItem>
                                          </SelectContent>
                                        </Select>
                                      </div>
                                      <div className="space-y-2">
                                        <Label htmlFor="edit-status">Stato</Label>
                                        <Select
                                          value={editingUser.status}
                                          onValueChange={(value) => setEditingUser({ ...editingUser, status: value })}
                                        >
                                          <SelectTrigger id="edit-status" className="border-muted-foreground/20">
                                            <SelectValue placeholder="Seleziona stato" />
                                          </SelectTrigger>
                                          <SelectContent>
                                            <SelectItem value="active">Attivo</SelectItem>
                                            <SelectItem value="inactive">Inattivo</SelectItem>
                                          </SelectContent>
                                        </Select>
                                      </div>
                                    </div>
                                  )}
                                  <DialogFooter>
                                    <Button variant="outline" onClick={() => setIsEditUserDialogOpen(false)}>
                                      Annulla
                                    </Button>
                                    <Button onClick={handleEditUser} className="shadow-sm">
                                      Salva Modifiche
                                    </Button>
                                  </DialogFooter>
                                </DialogContent>
                              </Dialog>

                              <Dialog
                                open={isDeleteUserDialogOpen && userToDelete?.id === user.id}
                                onOpenChange={(open) => {
                                  setIsDeleteUserDialogOpen(open)
                                  if (open) setUserToDelete(user)
                                }}
                              >
                                <DialogTrigger asChild>
                                  <Button
                                    variant="ghost"
                                    size="icon"
                                    className="hover:bg-red-100 dark:hover:bg-red-900/20"
                                  >
                                    <Trash2 className="h-4 w-4 text-red-500" />
                                    <span className="sr-only">Elimina</span>
                                  </Button>
                                </DialogTrigger>
                                <DialogContent>
                                  <DialogHeader>
                                    <DialogTitle>Elimina Utente</DialogTitle>
                                    <DialogDescription>
                                      Sei sicuro di voler eliminare questo utente? Questa azione non può essere
                                      annullata.
                                    </DialogDescription>
                                  </DialogHeader>
                                  {userToDelete && (
                                    <div className="py-4">
                                      <div className="flex items-center gap-3 p-4 rounded-lg bg-muted/30">
                                        <Avatar className="h-10 w-10 border-2 border-primary/10">
                                          <AvatarImage src={userToDelete.avatar} alt={userToDelete.name} />
                                          <AvatarFallback className="bg-primary/10 text-primary">
                                            {userToDelete.name
                                              .split(" ")
                                              .map((n) => n[0])
                                              .join("")}
                                          </AvatarFallback>
                                        </Avatar>
                                        <div>
                                          <div className="font-medium">{userToDelete.name}</div>
                                          <div className="text-sm text-muted-foreground">{userToDelete.email}</div>
                                        </div>
                                      </div>
                                    </div>
                                  )}
                                  <DialogFooter>
                                    <Button variant="outline" onClick={() => setIsDeleteUserDialogOpen(false)}>
                                      Annulla
                                    </Button>
                                    <Button variant="destructive" onClick={handleDeleteUser} className="shadow-sm">
                                      Elimina
                                    </Button>
                                  </DialogFooter>
                                </DialogContent>
                              </Dialog>
                            </div>
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          {/* Impostazioni Notifiche */}
          <TabsContent value="notifications" className="space-y-4">
            <Card className="border-none shadow-md dashboard-card">
              <CardHeader>
                <CardTitle>Impostazioni Notifiche</CardTitle>
                <CardDescription>Configura le notifiche del sistema</CardDescription>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="flex items-center justify-between p-4 rounded-lg bg-muted/30 hover:bg-muted/50 transition-colors">
                  <div className="space-y-0.5">
                    <Label htmlFor="lowStock" className="text-base">
                      Notifiche scorte basse
                    </Label>
                    <div className="text-sm text-muted-foreground">
                      Ricevi notifiche quando un prodotto scende sotto la soglia minima
                    </div>
                  </div>
                  <Switch
                    id="lowStock"
                    checked={notificationSettings.lowStock}
                    onCheckedChange={(checked) =>
                      setNotificationSettings({ ...notificationSettings, lowStock: checked })
                    }
                    className="data-[state=checked]:bg-primary"
                  />
                </div>

                <div className="flex items-center justify-between p-4 rounded-lg bg-muted/30 hover:bg-muted/50 transition-colors">
                  <div className="space-y-0.5">
                    <Label htmlFor="newSales" className="text-base">
                      Notifiche nuove vendite
                    </Label>
                    <div className="text-sm text-muted-foreground">
                      Ricevi notifiche quando viene registrata una nuova vendita
                    </div>
                  </div>
                  <Switch
                    id="newSales"
                    checked={notificationSettings.newSales}
                    onCheckedChange={(checked) =>
                      setNotificationSettings({ ...notificationSettings, newSales: checked })
                    }
                    className="data-[state=checked]:bg-primary"
                  />
                </div>

                <div className="flex items-center justify-between p-4 rounded-lg bg-muted/30 hover:bg-muted/50 transition-colors">
                  <div className="space-y-0.5">
                    <Label htmlFor="email" className="text-base">
                      Notifiche via email
                    </Label>
                    <div className="text-sm text-muted-foreground">
                      Ricevi notifiche anche via email oltre che nell'applicazione
                    </div>
                  </div>
                  <Switch
                    id="email"
                    checked={notificationSettings.email}
                    onCheckedChange={(checked) => setNotificationSettings({ ...notificationSettings, email: checked })}
                    className="data-[state=checked]:bg-primary"
                  />
                </div>
              </CardContent>
              <CardFooter className="justify-end">
                <Button onClick={saveNotificationSettings} className="shadow-md hover:shadow-lg transition-all">
                  <Save className="mr-2 h-4 w-4" />
                  Salva Impostazioni
                </Button>
              </CardFooter>
            </Card>

            <Card className="border-none shadow-md dashboard-card">
              <CardHeader>
                <CardTitle>Anteprima Notifiche</CardTitle>
                <CardDescription>Ecco come appariranno le notifiche nel sistema</CardDescription>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="rounded-lg border p-4 flex items-start gap-4 bg-gradient-to-r from-amber-50/50 to-transparent dark:from-amber-950/20 dark:to-transparent transition-all hover:shadow-md">
                  <div className="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-full">
                    <AlertTriangle className="h-5 w-5 text-amber-500" />
                  </div>
                  <div>
                    <h4 className="font-medium">Scorta bassa</h4>
                    <p className="text-sm text-muted-foreground">SIM Fastweb sotto la soglia minima (5 rimasti)</p>
                  </div>
                </div>

                <div className="rounded-lg border p-4 flex items-start gap-4 bg-gradient-to-r from-green-50/50 to-transparent dark:from-green-950/20 dark:to-transparent transition-all hover:shadow-md">
                  <div className="p-2 bg-green-100 dark:bg-green-900/30 rounded-full">
                    <CheckCircle className="h-5 w-5 text-green-500" />
                  </div>
                  <div>
                    <h4 className="font-medium">Nuova vendita</h4>
                    <p className="text-sm text-muted-foreground">Nuova vendita registrata: €249.00</p>
                  </div>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          {/* Backup e Ripristino */}
          <TabsContent value="backup" className="space-y-4">
            <Card className="border-none shadow-md dashboard-card">
              <CardHeader>
                <CardTitle>Backup e Ripristino Dati</CardTitle>
                <CardDescription>Esporta o importa i dati del sistema</CardDescription>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="space-y-2 p-4 rounded-lg bg-muted/30">
                  <h3 className="text-lg font-medium flex items-center gap-2">
                    <Download className="h-5 w-5 text-primary" />
                    Esporta Dati
                  </h3>
                  <p className="text-sm text-muted-foreground">
                    Esporta tutti i dati del sistema in un file JSON. Questo file può essere utilizzato per ripristinare
                    i dati in caso di necessità.
                  </p>
                  <Button onClick={handleExportData} className="mt-2 shadow-md hover:shadow-lg transition-all">
                    <Download className="mr-2 h-4 w-4" />
                    Esporta Dati
                  </Button>
                </div>

                <div className="space-y-2 p-4 rounded-lg bg-muted/30">
                  <h3 className="text-lg font-medium flex items-center gap-2">
                    <Upload className="h-5 w-5 text-primary" />
                    Importa Dati
                  </h3>
                  <p className="text-sm text-muted-foreground">
                    Importa dati da un file JSON precedentemente esportato. Attenzione: questa operazione sovrascriverà
                    tutti i dati esistenti.
                  </p>
                  <div className="flex items-center gap-4 mt-2">
                    <Button variant="outline" asChild className="shadow-sm hover:shadow-md transition-all">
                      <label className="cursor-pointer">
                        <Upload className="mr-2 h-4 w-4" />
                        Seleziona File
                        <input type="file" accept=".json" className="hidden" onChange={handleImportData} />
                      </label>
                    </Button>
                  </div>
                </div>

                <div className="space-y-2">
                  <h3 className="text-lg font-medium flex items-center gap-2">
                    <AlertTriangle className="h-5 w-5 text-amber-500" />
                    Backup Automatici
                  </h3>
                  <p className="text-sm text-muted-foreground">
                    I dati vengono salvati automaticamente nel browser. Per una maggiore sicurezza, esporta regolarmente
                    i dati e conservali in un luogo sicuro.
                  </p>
                  <div className="rounded-lg bg-gradient-to-r from-amber-50 to-amber-50/20 dark:from-amber-950/50 dark:to-amber-950/10 p-4 text-amber-800 dark:text-amber-200 mt-2 shadow-sm">
                    <div className="flex items-center gap-2">
                      <AlertTriangle className="h-5 w-5" />
                      <span className="font-medium">Nota importante</span>
                    </div>
                    <p className="mt-1 text-sm">
                      I dati sono attualmente memorizzati solo nel browser locale. La cancellazione della cache o
                      l'utilizzo di un browser diverso comporterà la perdita dei dati non esportati.
                    </p>
                  </div>
                </div>
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </main>
    </div>
  )
}

